<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <title>ezSQL代码生成器</title>
    <script src="jquery-1.8.0.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $().ready(function () {
            $("textarea").each(function () {
                ResizeTextarea(document.getElementById($(this).attr("id")));
            });
        });
        var minHeight = 100;
        // 最大高度，超过则出现滚动条
        var maxHeight = 300;
        function ResizeTextarea(obj) {
            var t = obj;
            h = t.scrollHeight;
            h = h > minHeight ? h : minHeight;
            //h = h > maxHeight ? maxHeight : h;
            t.style.height = h + "px";
        }
    </script>
</head>

<body>
<?php
$cfg_host = "127.0.0.1";    //主机名
$cfg_username = "root";            //用户名
$cfg_password = "163888";                //用户密码
$cfg_database = "magic";        //数据库名

$Connect = mysql_connect($cfg_host, $cfg_username, $cfg_password);
mysql_select_db($cfg_database, $Connect);
$Sql = "show Tables from " . $cfg_database;
$Query = mysql_query($Sql, $Connect);
echo "<select name=TableName onchange=\"location.href='?TableName='+this[selectedIndex].value;\">";

echo "<option value=''>select</option>";

while ($Table = mysql_fetch_array($Query)) {
    echo "<option value=" . $Table[0] . ">" . $Table[0] . "</option>";
}
echo "</select><BR>";

$TableName = @$_REQUEST['TableName'];
if ($TableName) {
    $T = mysql_list_fields($cfg_database, $TableName, $Connect);
    $Fields = mysql_num_fields($T);
    $Insert = "\"insert into " . $TableName . "(";
    $Update = "\"update " . $TableName . " set ";
    $temp = "";
    $Read = "\t\t\t$" . $TableName . " = \$db->get_row(\"SELECT * FROM `" . $TableName . "`  WHERE id =\".\$id)\n\t\t\tif($" . $TableName . ")\n\t\t\t{\n";
    $Lang = "";
    $Post = "";
    $list2json="\$rows=\"'Rows':[\";\nforeach (\$".$TableName."s as \$".$TableName.") \n{\n\t\$rows.=\"{";

    for ($i = 0; $i < $Fields; $i++) {
        $f = mysql_field_name($T, $i);
        if ($i == 0) {
            $Insert = $Insert . $f;
            $Update = $Update . $f . "=\".$" . $f;
            $temp = "'\".$" . $f;
            $Read .= "\t\t\t\t$" . $f . "=$" . $TableName . "->" . $f . ";\n";
            $Field = "$" . $f;
            $Post = "\$" . $f . "=\$_POST[\"" . $f . "\"];\n";
            $list2json .= "'".$f."':'\".$".$TableName."->".$f.".\"',";
        } else {
            $Insert = $Insert . "," . $f;
            $Update = $Update . ".\"," . $f . "=\".$" . $f;
            $temp = $temp . ".\"','\".$" . $f;
            $Read .= "\t\t\t\t$" . $f . "=$" . $TableName . "->" . $f . ";\n";
            $Lang = $Lang . "'" . $f . "'\t=>\t'" . $f . "',\n";
            $Field = $Field . ",$" . $f;
            $Post .= "\$" . $f . "=\$_POST[\"" . $f . "\"];\n";
            $list2json .= "'".$f."':'\".$".$TableName."->".$f.".\"',";
        }
    }
    $Read .= "\t\t\t}\n";
    $Insert = $Insert . ") values(" . $temp . ".\"')\"";
    $Update = $Update . ".\" where ID=\".$" . "ID";
    $list2json=trim($list2json,",");
    $list2json .="},\";\n}\n\$rows=trim(\$rows,\",\")+\"]\";";
    echo "LANG:<Br><textarea id='langarea' style='width:100%;height:100px'>";
    echo $Lang;
    echo "</textarea>";
    echo "POST:<Br><textarea id='postarea' style='width:100%;height:100px'>";
    echo $Post;
    echo "</textarea><br>";
    echo "READ:<Br><textarea id='readarea' style='width:100%;height:100px'>";
    echo $Read;
    echo "</textarea>";
    echo "INSERT SQL:<Br><textarea id='insertarea' style='width:100%;height:100px'>";
    echo $Insert;
    echo "</textarea><br>";
    echo "UPDATE SQL:<Br><textarea id='updatearea' style='width:100%;height:100px'>";
    echo $Update;
    echo "</textarea><br>";
    echo "LIST2JSON:<Br><textarea id='jsonarea' style='width:100%;height:100px'>";
    echo $list2json;
    echo "</textarea><br>";
    echo $Field;
}
?>

</body>
</html>
