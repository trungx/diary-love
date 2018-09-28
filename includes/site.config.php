<?php
// Thiết lập thông tin trang nhật ký

    // Tiêu đề trang nhật ký
    $web_title = 'DiaryLove 2.0';

    /* Sử dụng cho
       1. love      <==> Nhật ký tình yêu
       2. friend    <==> Nhật ký bạn bè  */
    $web_for = 'love';

    /* Tùy chọn cho khối thông tin cá nhân */
    $web_about = '1';
        // 1 : sử dụng 1 ảnh dùng ảnh khi login $web_avatar
        // 2 : sử dụng nhiều ảnh và chiếu bằng flash
        // 3 sử dụng 3 ảnh giống yahoo 360

    // Ảnh about mặc định
    $web_avatar = 'images/cover.jpg';
    /* Mảng chứa ảnh khi chiếu nhiều ảnh */
    $web_about_array = 'http://img.msg.yahoo.com/avatar.php?yids=girlkieukihadong||images/cover.jpg||http://www.gravatar.com/avatar/9fc217028846fe680dc1566f45e3ee00';
    // ID của album ảnh sẽ chiếu
    $web_about_f = '1';

    /* Lời nhắn nhủ trên site */
    $web_boy_comment = 'Anh yêu Em nhiều lắm Em có biết không? Nếu 1 mai đôi ta có phải xa nhau anh chỉ mong mình sẽ mãi nhớ đến nhau :*';
    $web_girl_comment = "Em yêu Anh nhiều lắm Anh có biết không? Mình hãy mãi bên nhau không xa rời anh nhé! :)";


    // Ngôn ngữ sử dụng
    $web_language = 'vietnam';

    // Giao diện chính sử dụng cho nhật ký
    $web_template = 'default';

    // Từ ngũ xấu cấm sử dụng
    $web_badword = 'lồn,buồi,cặc,dái,địt,đụ';
    // Số nhật ký hiển thị trên trang
    $web_diary_limit = '3';
    // Số ảnh sẽ hiển thị trên trang
    $web_gallery_limit = '4';
    // Số ca khúc hiển thị trên trang
    $web_music_limit = '25';
    // Số ước nguyện hiển thị trên trang
    $web_wish_limit = '30';

    /* Thời gian theo GMT */
    $web_timezones = 'SST';

    /* Hình vui */
    $emotion = array(
        1 => ':)',		    2 => ':(',
		3 => ';)',		    22 => ':|',	            	14 => 'X(',		    15 => ':>',
		8 => ':X',		    4 => ':D',		            27 => '=;',	    	10 => ':P',
        18 => '#:-S',		36 => '<:-P',		        42 => ':-SS',       48 => '<):)',
		63 => '[-O<',       67 => ':)>-',		        77 => '^:)^',		106 => ':-??',
        25 => 'O:)',        26 => ':-B',		        28 => 'I-)',		29 => '8-|',
        30 => 'L-)',        31 => ':-&',		        32 => ':-$',		33 => '[-(',
        34 => ':O)',        35 => '8-}',		        7 => ':-/',			37 => '(:|',
        38 => '=P~',        39 => ':-?',		        40 => '#-O',	    41 => '=D>',
        9 => ':">',         43 => '@-)',		        44 => ':^O',		45 => ':-W',
        46 => ':-<',        47 => '>:P',		        11 => ':*',	        12 => '=((',
		13 => ':-O',		16 => 'B-)',                17 => ':-S',		5 => ';;)',
        19 => '>:)',        62 => ':-L',                20 => ':((',		64 => '$-)',
        65 => ':-"',        66 => 'B-(',                21 => ':))',		68 => '[-X',
        69 => '\:D/',       70 => '>:/',       	        71 => ';))',        76 => ':-@',
        23 => '/:)',        78 => ':-J',                100 => ':)]',		101 => ':-C',
        103 => ':-H',       104 => ':-T',		        105 => '8->',		24 => '=))',
        108 => ':O3',       50 => '3:-O',		        51 => ':(|)',		53 => '@};-',
		55 => '**==',		56 => '(~~)',				58 => '*-:)',       49 => ':@)',
        52 => '~:>',        54 => '%%-',                57 => '~O)',        59 => '8-X',
        60 => '=:)',	    61 => '>-)',                79 => '(*)',
	);
?>