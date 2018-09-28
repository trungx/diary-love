<?php
	class mosHTML {
		function makeOption( $value, $text='', $value_name='value', $text_name='text' ) {
			$obj = new stdClass;
			$obj->$value_name = $value;
			$obj->$text_name = trim( $text ) ? $text : $value;
			return $obj;
		}
		/**
		* Generates an HTML select list
		* @param array An array of objects
		* @param string The value of the HTML name attribute
		* @param string Additional HTML attributes for the <select> tag
		* @param string The name of the object variable for the option value
		* @param string The name of the object variable for the option text
		* @param mixed The key that is selected
		* @returns string HTML for the select list
		*/
		
		function select( $arr, $tag_name, $tag_attribs='', $selected=NULL ) {
			// check if array
			if ( is_array( $arr ) ) {
				reset( $arr );
			}
	
			$html 	= "\n<select  style=\"margin-bottom: 5px;\" name=\"$tag_name\" id=\"$tag_name\" $tag_attribs>";
			foreach ($arr as $k => $v) {
				
				if ($v[0]==$selected) $extra = 'selected="selected"';
				else $extra = '';
				$html .= '<option value="'.$v[0].'" '.$extra.'>'.$v[1].'</option>';
			}
			$html .= "\n</select>\n";
	
			return $html;
		}
		/**
		* Generates an HTML radio list
		* @param array An array of objects
		* @param string The value of the HTML name attribute
		* @param string Additional HTML attributes for the <select> tag
		* @param mixed The key that is selected
		* @param string The name of the object variable for the option value
		* @param string The name of the object variable for the option text
		* @returns string HTML for the select list
		*/
		function radiolist( $arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
			reset( $arr );
			$html = "";
			for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
				$k = $arr[$i]->$key;
				$t = $arr[$i]->$text;
				$id = ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

				$extra = '';
				$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
				if (is_array( $selected )) {
					foreach ($selected as $obj) {
						$k2 = $obj->$key;
						if ($k == $k2) {
							$extra .= " selected=\"selected\"";
							break;
						}
					}
				} else {
					$extra .= ($k == $selected ? " checked=\"checked\"" : '');
				}
				$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"".$k."\"$extra $tag_attribs />";
				$html .= "\n\t<label style='cursor: pointer;' for=\"$tag_name$k\">$t</label>&nbsp;";
			}
			$html .= "\n";
	
			return $html;
		}


		/**
		* Writes a yes/no radio list
		* @param string The value of the HTML name attribute
		* @param string Additional HTML attributes for the <select> tag
		* @param mixed The key that is selected
		* @returns string HTML for the radio list
		*/
		function yesno( $tag_name, $tag_attribs, $selected, $yes='Mở ra', $no='Đóng' ) {
			$arr = array(
				mosHTML::makeOption( 'yes', $yes ),
                mosHTML::makeOption( 'no', $no )

			);
	
			return mosHTML::radiolist( $arr, $tag_name, $tag_attribs, $selected );
		}
	}
?>