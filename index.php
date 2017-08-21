<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Misha Levitsky"/>
    <script src="other/jquery-1.2.6.pack.js"></script>
    <title>Michael Levitsky - Test</title>
</head>
<body>
<script type="text/javascript">
    var total = 0;
    function add_new_item() {
        total++;
        $('<tr>')
            .attr('id', 'tr_image_' + total)
            .css({lineHeight: '20px'})
            .append(
                $('<td>')
                    .attr('id', 'td_title_' + total)
                    .css({paddingRight: '5px', width: '200px'})
                    .append(
                        $('<input type="text" />')
                            .css({width: '200px'})
                            .attr('id', 'input_title_' + total)
                            .attr('name', 'input_title_' + total)
                    )
            )
            .append(
                $('<td>')
                    .css({width: '60px'})
                    .append(
                        $('<span id="progress_' + total + '" class="padding5px"><a  href="#" onclick="$(\'#tr_image_' + total + '\').remove();" class="ico_delete"><img src="delete.png" alt="del" border="0"></a></span>')
                    )
            )
            .appendTo('#table_container');

    }
    $(document).ready(function () {
        add_new_image();
    });
</script>


<div>
    <form action="" method="post">
        <table id="table_container">
            <tr>
                <td width="100px" colspan="2"><strong>Введіть URL</strong></td>

            </tr>
        </table>
        <br/>
        <input type="button" value="Добавити поле" id="add" onclick="return add_new_item();">
        <input type="submit" value="Парсинг">

    </form>

    <div>
        <?php
        include_once('other/phpQuery.php');
        include_once('db/Db.php');
        $n = count($_POST);
        $key = array_keys($_POST);
        for ($i = 0; $i < $n; $i++) {
            echo "<b>" . $_POST[$key[$i]] . "</b>";
            $doc = $_POST[$key[$i]];
            $site = file_get_contents($doc);
            $pq = phpQuery::newDocument($site);
            $res = $pq->find('.seo-text');
            $itemCheck = $res->stack();
            if (count($itemCheck) > 0) {
                foreach ($res as $elem) {
                    $des = pq($elem)->text();
                    $list = str_replace(array(" "), '', $des);
                    $count = mb_strlen($list, 'utf-8');
                    echo "<h3>Текст присутній!</h3>";
                    echo "<h2>Об'єм символів без пробілів та без тегів:" . $count . "</h2>";
                    echo $des ;
                    $db = Db::getConnection();
                    $sql = $db->exec("INSERT IGNORE INTO parser (link_url, count_text, text_link) VALUES ('$doc', '$count', '$des')");
                }   if ($sql) {
                    echo "<h4><i>Добавлено в базу даних. Унікальний контент. </i></h4>";
                } else {
                    echo "<h4><i>Не добавлено в базу даних. Такі дані уже є.</i></h4>";
                }
            } else {
                echo "<h2>Тексту немає</h2>";
                echo "Не добавлено в базу даних.";
            }
            echo "<hr>";
        }
        ?>
    </div>
</div>
</body>
</html>




