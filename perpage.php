<?php
define("PERPAGE_LIMIT",10);
function connectDB(){
mysql_connect("localhost","root","");
mysql_select_db("matrix");

}
function getFAQ() {
$sql = "SELECT * FROM matrix.comment";

// getting parameters required for pagination
$currentPage = 1;
if(isset($_GET['pageNumber'])){
$currentPage = $_GET['pageNumber'];
}
$startPage = ($currentPage-1)*PERPAGE_LIMIT;
if($startPage < 0) $startPage = 0;
$href = "/php_samples/perpage.php?";

//adding limits to select query
$query =  $sql . " limit " . $startPage . "," . PERPAGE_LIMIT; 
$result = mysql_query($query);
while($row=mysql_fetch_array($result)) {
$questions[] = $row;
}

if(is_array($questions)){
$questions["page_links"] = paginateResults($sql,$href);
return $questions;
}
}

//function creates page links
function pagination($count, $href) {
$output = '';
if(!isset($_REQUEST["pageNumber"])) $_REQUEST["pageNumber"] = 1;
if(PERPAGE_LIMIT != 0)
$pages  = ceil($count/PERPAGE_LIMIT);

//if pages exists after loop's lower limit
if($pages>1) {
if(($_REQUEST["pageNumber"]-3)>0) {
$output = $output . '<a href="' . $href . 'pageNumber=1" class="page">1</a>';
}
if(($_REQUEST["pageNumber"]-3)>1) {
$output = $output . '...';
}

//Loop for provides links for 2 pages before and after current page
for($i=($_REQUEST["pageNumber"]-2); $i<=($_REQUEST["pageNumber"]+2); $i++)	{
if($i<1) continue;
if($i>$pages) break;
if($_REQUEST["pageNumber"] == $i)
$output = $output . '<span id='.$i.' class="current">'.$i.'</span>';
else				
$output = $output . '<a href="' . $href . "pageNumber=".$i . '" class="page">'.$i.'</a>';
}

//if pages exists after loop's upper limit
if(($pages-($_REQUEST["pageNumber"]+2))>1) {
$output = $output . '...';
}
if(($pages-($_REQUEST["pageNumber"]+2))>0) {
if($_REQUEST["pageNumber"] == $pages)
$output = $output . '<span id=' . ($pages) .' class="current">' . ($pages) .'</span>';
else				
$output = $output . '<a href="' . $href .  "pageNumber=" .($pages) .'" class="page">' . ($pages) .'</a>';
}

}
return $output;
}

//function calculate total records count and trigger pagination function	
function paginateResults($sql, $href) {
$result  = mysql_query($sql);
$count   = mysql_num_rows($result);
$page_links = pagination($count, $href);
return $page_links;
}
?>
<html>
<head>
<title>PHP Pagination</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
<head>
<body>
<table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
<tr class="tableheader">
<td>Frequently Asked Questions</td>
</tr>
<?php
connectDB();
$questions = getFAQ();
if(is_array($questions)) {
for($i=0;$i<count($questions)-1;$i++) {
?>
<tr class="tablerow">
<td><h2><?php echo $questions[$i]["comment"]; ?></h2><br/>
<span class="date">Added Date: <?php echo $questions[$i]["service_id"]; ?></span></td>
</tr>
<?php
}
?>
<tr class="tableheader">
<td colspan="2"><?php echo $questions["page_links"]; ?></td>
</tr>
<?php
}
?>
</table>
</body>
</html>