<?
############# CLASS MYSQL #####################
class mysql{
    var $link;
	var $log_file = 'log.txt';
	var $log_error = 1;
// CONNECT
function connect($db_host,$db_username,$db_password,$db_database) {
    $this->link=@mysql_connect($db_host,$db_username,$db_password);
    if ($this->link){
    if($this->link=@mysql_select_db($db_database,$this->link)) return $this->link;
    else $this->show_error('Error connect to server '.mysql_error());
    }else{$this->show_error('Error connect to database '.mysql_error());}
    }
// QUERY
function query($query){
    return @mysql_query($query);}
// FETCH ARRAY
function fetch_array($query,$sql=MYSQL_BOTH){
return @mysql_fetch_array($query,$sql);}
//NUM ROWS
function num_rows($input){
return @mysql_num_rows($input);}
// INSERT
function insert ($table,$field) {
		global $table_prefix;
			if (is_array($field)) {
				reset ($field);
				$sql = 'INSERT INTO ' .$table_prefix.$table . ' (';
				$rows = ''; $values = '';
				while (list($k,$v) = each($field)) {
					$rows .= $k . ',';
					$values .= "'" . $v . "',";
				}
				$rows = substr($rows,0,strlen($rows) - 1);
				$values = substr($values,0,strlen($values) - 1);
				$sql .= $rows . ') VALUES (' . $values . ')';
				return $this->query ($sql);
			}
		}
// DELETE
function delete ($table,$dk) {
		global $table_prefix;
			return $this -> query ("DELETE FROM ".$table_prefix."$table WHERE $dk");
		}
// UPDATE
function update ($table,$field,$dk='') {
global $table_prefix;
			if (is_array($field)) {
				reset ($field);
				$sql = 'UPDATE '. $table_prefix.$table . ' SET ';
				$rows = ''; $values = '';
				while (list($k,$v) = each($field)) {
					$sql .= $k . "=";
					$sql .= "'" . $v . "',";
				}
				$sql = substr($sql,0,strlen($sql) - 1);
			}
			if ($dk != '') $sql .= ' WHERE ' . $dk;
			return $this->query($sql);
		}
// SHOW ERROR
	function show_error($q){
    global $admin_mail;
		if ($this->log_error) {
			$file_name = $this->log_file;
           	$fp = fopen($file_name,'a');
			flock($fp,2);
			fwrite($fp,"### ".date('H:s:i d-m-Y')." ###\n");
			fwrite($fp," ".$q."\n");
			flock($fp,1);
			fclose($fp);
		}
		die("<br /><br /><center><b>Trang web đang bị lỗi vui lòng thông báo với admin về lỗi này!<br /><img src='images/logo.gif'>");
	}
    }
?>