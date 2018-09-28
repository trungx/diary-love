
function check_email(s){if(s=="") return false;
if(s.indexOf(" ")>0) return false;
if(s.indexOf("@")==-1)return false;
var i=1; var sLength=s.length;
if(s.indexOf(".")==-1) return false;
if(s.indexOf("..")!=-1)return false;
if(s.indexOf("@")!=s.lastIndexOf("@")) return false;
if(s.lastIndexOf(".")==s.length-1)return false;
var str="abcdefghikjlmnopqrstuvwxyz-@._0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
for(var j=0;j<s.length;j++)
if(str.indexOf(s.charAt(j))==-1)
return false;return true;
}

function today(){
    dayname = new Array(lang['cn'], lang['t2'], lang['t3'], lang['t4'], lang['t5'], lang['t6'], lang['t7']);
    monthname = new Array("Tháng Một", "Tháng Hai", "Tháng Ba", "Tháng Tư", "Tháng Năm", "Tháng Sáu", "Tháng Bảy", "Tháng Tám", "Tháng Chín", "Tháng Mười", "Tháng Mười Một", "Tháng Mười Hai");
    now = new Date();
    daynum = now.getDay();
    day = dayname[daynum];
    date = now.getDate();
    monthnum = now.getMonth();
    year = now.getYear();
    month = monthname[monthnum];
    $('#today').html("Hôm nay "+day+" ngày "+date+" "+month+" năm "+year);
}

function scroll_to(id){
    $.scrollTo(id, 1500);
}

function change_capcha(){
  $("#capcha-img").empty().html("<img style=\"margin-bottom:-3px;\" src='includes/security.php?"+Math.random()+"'>");
  return false;
}

function showform(form) {
	if ($("#"+form+"_close").val() == "show") {
		$("#"+form).slideDown("normal");
		$("#"+form+"_close").val("hide");
	}
	else {
		$("#"+form).slideUp("normal").fadeOut(2000);
		$("#"+form+"_close").val("show");
	}
}

// SHOW LOADING AND ERROR
function hide_err(div,message,time,back){
  imgicon = "<img src='images/icon/err.png'>";
  $('#all').addClass('hide');
  $('#'+div).slideDown('normal').html('<center><b><br>'+imgicon+' '+message+'</center></b><br>');
  $('#clock').countDown({
    startNumber: time,
    callBack: function() {
        $('#'+div).slideUp('normal');
        $('#all').removeClass('hide');
        back;
    }
  });
}

function show_loading(div){
	    $("#"+div).fadeTo("fast", 0.09);
      //  $("#showloading").slideDown('slow').html("<img src='images/loading.gif'><br>Đang tải dữ liệu...");
        $("#showloading").fadeTo('fast', 1).show().html("<img src='images/loading.gif'><br>Đang tải dữ liệu...");
}

function hide_loading(){
        $("#showloading").fadeOut('normal');
}

function hideform(div){
        $("#"+div).fadeOut('normal');
        $('#all').removeClass('hide');
}


function web_title(title){
    document.title = 'DiaryLove 2.0 - '+title;
}



function loadmain(title,div,link){
    loadblock(link);
    web_title(title);
    $.ajax({
        beforeSend: function(){
            show_loading(div);
        },
        type: "POST",
        url: "index.php",
        data: ""+link,
        success: function(data){
            hide_loading();
            $("#"+div).fadeTo("norman", 1).html(data);
        }
    });

}

function loadblock(link){
   $.ajax({
        beforeSend: function(){
            show_loading('bockmain');
        },
        type: "POST",
        url: "index.php",
        data: "block="+link,
        success: function(data){
          hide_loading();
          $("#blockmain").fadeTo("norman", 1).html(data);
          //load_message();
        }
    });
}

function show_tooltip(code,type){
    $('#showloading').slideDown('normal').html(code);
      //  alert('abc');
    if(type=='auto'){
      $('#clock').countDown({
        startNumber: 3,
        callBack: function() {
            $('#showloading').slideUp('normal').hide('normal');
        }
      });
    }
}

function showemot(){
    $.ajax({
       /* beforeSend: function(){
            $('#all').addClass('hide');
        },     */
        type: "post",
        url: "index.php",
        data: "emotion=true",
        success: function(data){
            $('#emot').slideDown('normal').html(data);
        }
    });
}

function show_wish(id,more,div){
    // more : tuy chon khi xem cho cac chuc nang khac
    if(more){
        dat = ""+more+"&id="+id;
    }else{
        dat = "wish=view&id="+id;
    }
    $.ajax({
        beforeSend: function(){
            $('#all').addClass('hide');
        },
        type: "post",
        url: "index.php",
        data: dat,
        success: function(data){
            if(div){
                $('#'+div).show().html(data);
            }else{
                $('#loading').slideDown('normal').html(data);
            }
        }
    });
}




function hide_tooltip(){
    hide_loading();
}

// MEMBER LOGIN
function login(){
  password = $('#password').val();
  if(password=='' || password == 'password'){
      hide_err('loading',lang[0],3);
      $('#password').focus();
      return false;
  }
  $.ajax({
        type: "post",
        url: "index.php",
        data: {
            member: "login",
            password: password
        },
        success: function(data){
            err= data.toString();
            if(data=='err'){
                  hide_err('loading',lang[1],2);
            	  $('#password').val('').focus();
                  return false;
            }
            if(err=='1'){
                var loading = lang[4];
                var hi = lang[2];
            }
            if(err=='2'){
                var loading = lang[5];
                var hi = lang[3];
            }
              hide_err('loading','<br>'+hi+'<br>'+loading,3);
             $.ajax({
               /* beforeSend: function(){
                    show_loading('sitemain');
                },*/
                type: "post",
                url: "index.php",
                data: "home=home",
                success: function(data){
                  //  hide_loading();
                    $('#sitemain').fadeTo('normal', 1).html(data);
                }
             });
             web_title(webname);
        }
  });

return false;
}

function logout(){
    $.ajax({
        beforeSend: function(){
            show_loading('sitemain');
            hideform('loading');
        },
        type: "post",
        url: "index.php",
        data: "member=logout",
        success: function(data){
          hide_loading();
           //document.location.href = "index.php";
           $('#sitemain').fadeTo('normal', 1).html(data);

        }
    });
}

function check_logout(){
    $.ajax({
        beforeSend: function(){
            $('#all').addClass('hide');
        },
        type: "post",
        url: "index.php",
        data: "member=checklogout",
        success: function(data){
            $('#loading').slideDown('normal').html(data);
        }
    });
}




// MESSAGE LOADIND
function load_message(){
    $.ajax({
       type: "post",
       url: "index.php",
       data: "member=mess_test",
       success: function(data){
            err = data.toString();
            if(!err || err=='0'){
                return false;
            }else{
                $('#all').addClass('hide');
                $('#message').show().html(data);
            }
       }
    });
    return false;
}



function viewpages(div,link_page){
    $.ajax({
        beforeSend: function(){
            show_loading(div);
            scroll_to('#top');
        },
        type: "POST",
        url: "index.php",
        data: ''+link_page,
        success: function(data){
            hide_loading();
            $("#"+div).fadeTo("fast", 1).html(data);
        return false;
        }
    });
}

function play(link,info){
    $.ajax({
        type: "post",
        url: "index.php",
        data: ''+link,
        success: function(data){
            $('#play').html(data);
        }
    });
    if(info){
        $.ajax({
            type: "POST",
            url: "index.php",
            date: ''+info,
            success: function(data){
                $('#song-info').slideDown('slow').html(data);
            }
        });
    }
}

function playlist(id,type){
    var ok = true;
    if(type=='remove'){
        if(!confirm('That su muon go bo?')==true){
            ok = false;
        }
    }
    if(ok==true){
      $.ajax({
          type: "post",
          url: "index.php",
          data: "music=favorite_add&f="+type+"&id="+id,
          success: function(data){
          if(data=='daco'){
            alert('Ca khuc da ton tai trong playlist');
            return false;
          }
             // refesh_fav(id);
             $('#favorite-image-'+id).html(data);
          }
      });
    }else{
        return false;
    }
}



function newpost(submit,link){
     $.ajax({
        beforeSend: function(){
            $('#all').addClass('hide');
        },
        type: "POST",
        url: "index.php",
        data: ""+link,
        success: function(data){
            $('#post-mode').html(data);
            $('#submit-mode').val(submit);
            $('#post').removeClass('post-hide').addClass('post-show');
        }
    });
    return false;
}

function select_thumb(type){
    var link_thumb = prompt("Chọn đường dẫn đến link ảnh\nNếu link ảnh nằm trong server bạn không cần thêm http://.","");
    if(link_thumb!=null&&link_thumb!=''){

      if(type=='thumb'){
          $("#post-thumb").val(link_thumb);
      }else{
          $("#post-link").val(link_thumb);
      }
          $("#pic-preview").fadeTo("normal", 0.09).fadeTo("normal", 1).html("<a href=\""+link_thumb+"\" class=\"highslide\" onclick=\"return hs.expand(this)\"><img class='image' src=\""+link_thumb+"\" width=\"100\"></a>");
    }
}

function comment_submit(){
    type = $('#post-type').val();
    catid = $('#post-catid').val();
    level = $('#post-level').val();
    content = $('#post-comment').val();
    if(content==''){
        alert('Chua cho noi dung');
        $('#post-comment').focus();
        return false;
    }
    $.ajax({
        type: "post",
        url: "index.php",
        data: {
            comment: 'submit_ok',
            catid: catid,
            content: content,
            type: type,
            level: level
            },
        success: function(data){
            $('#'+type+'-comment-list').fadeTo('normal', 1).html(data);
            $('#post-comment').val('');
        }
    });
    return false;
}


function diary_submit(){
    title = $('#post-title').val();
    id = $('#post-id').val();
    catid = $('#post-category').val();
    content = $('#post-content').val();
    weather = $('#post-weather').val();
    feeling = $('input[@name=post-feeling]:checked').val();
    type =  $('#post-type').val();
    if(title==''){
        alert('vui lòng nhập tiêu đề');
        $('#post-title').focus();
        return false;
    }
    if(content==''){
        alert('Chưa có nội dung gì rồi');
        $('#post-content').focus();
        return false;
    }
    diary = id>0?'edit_ok':'submit';
   $.ajax({
        beforeSend: function(){
            show_loading('main');
        },
        type: "post",
        url: "index.php",
        data: {
            diary: diary,
            id: id,
            catid: catid,
            title: title,
            type: type,
            content: content,
            weather: weather,
            feeling: feeling
            },
        success: function(data){
            hide_loading();
           if(diary=='edit_ok'){
                viewpages('main','diary=view&id='+id);
           }else{
                viewpages('main','diary=list&order=new');
           }
        }
    });
}


function gallery_submit(){
    g_title = $('#post-title').val();
    g_content = $('#post-content').val();
    g_thumb = $('#post-thumb').val();
    g_type = $('#post-type').val();
    g_id = $('#post-id').val();
    g_link = $('#post-link').val();
    g_album = $('#post-album').val();
    g_category = $('#post-category').val();

    if(g_title==''){
        alert('Vui long nhap tieu de');
        $('#post-title').focus();
        return false;
    }
    if(g_thumb==''){
        alert('Chua nhap duong dan thumb');
        $('#post-thumb').focus();
        return false;
    }
    if(g_type=='pic' && g_link==''){
        alert('Chua nhap duong dan anh');
        $('#post-link').focus();
        return false;
    }
    g_mode = 'add_submit';
    $.ajax({
        type: "post",
        url: "index.php",
        data: {
            gallery: g_mode,
            title: g_title,
            content: g_content,
            thumb: g_thumb,
            album: g_album,
            type: g_type,
            link: g_link,
            category: g_category,
            id: g_id
        },
        success: function(data){
          //  alert('Dang anh thanh cong!');
          $('#main').fadeTo('normal', 1).html(data);
          return false;
        }
    });
}


function music_submit(){
    title =  $('#post-title').val();
    type = $('#post-type').val();
    content =  $('#post-content').val();
    id = $('#post-id').val();
    link = $('#post-link').val();
    thumb = $('#post-thumb').val();
    catid =  $('#post-category').val();
    abid = $('#post-album').val();
    singid = $('#post-singer').val();
    if(title==''){
        alert('chua nhap tieu de');
        $('#post-title').focus();
        return false;
    }
    if(type=='song' && link ==''){
        alert('chua nhap duong dan file nhac');
        $('#post-link').focus();
        return false;
    }
    $.ajax({
        type: "post",
        url: "index.php",
        data: {
                music: "new_submit",
                type: type,
                title: title,
                content: content,
                id: id,
                singid: singid,
                link: link,
                thumb: thumb,
                catid: catid,
                abid: abid
            },
        success: function(data){
            $('#main').fadeTo('fast', 1).html(data);

        }
    });


}


function wish_submit(){
    content =  $('#post-content').val();
    category = $('#post-category').val();
    if(content==''){
        alert('Chua nhap noi dung uoc nguyen');
        $('#post-content').focus();
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('main');
        },
        type: "post",
        url: "index.php",
        data: {
            wish: "wish_submit",
            content: content,
            category: category
        },
        success: function(data){
            hide_loading();
            $('#main').fadeTo('fast', 1).html(data);
        }
    });
}



function change_pass(){
    pass_old = $('#pass_old').val();
    pass_new = $('#pass_new').val();
    pass_c = $('#pass_c').val();
    capcha = $('#capcha').val();
    if( pass_old==''){
        alert('Chua nhap mat khau hien tai');
        $('#pass_old').focus();
        return false;
    }
    if(pass_new==''){
        alert('chua nhap mat khau moi');
        $('#pass_new').focus();
        return false;
    }
    if(pass_c==''){
        alert('chua xac nhan mat khau');
        $('#pass_c').focus();
        return false;
    }
    if(pass_new!=pass_c){
        alert('xac nhan mat khau khong chinh xac');
        $('#pass_new').focus();
        return false;
    }
    if(capcha==''){
        alert('chua nhap ma bao ve');
        $('#capcha').focus();
        return false;
    }
    $.ajax({
        beforeSend: function(){
            $('#cmain').fadeTo('fast', 0.09);
        },
        type: "post",
        url: "index.php",
        data: { member: "change_pass_ok",
                pass_old: pass_old,
                pass_new: pass_new,
                capcha: capcha
        },
        success: function(data){
            err = data.toString();
            if(err=='err'){
                alert('Mat khau cu khong chinh xac');
                $('#pass_old').focus();
                return false;
            }
            if(err=='capcha'){
                alert('Xac nhan ma bao ve khong dung');
                change_capcha();
                $('#capcha').val('').focus();
                $('#cmain').fadeTo('fast', 1);
                return false;
            }
                alert('Thay doi mat khau thanh cong');
                $('#cmain').fadeTo('fast', 1).html(data);
        }
    });
}


function change_info(){
    username = $('#username').val();
    fullname = $('#fullname').val();
    day = $('#day').val();
    month = $('#month').val();
    year = $('#year').val();
    yahoo = $('#yahoo').val();
    email = $('#email').val();
    capcha = $('#capcha').val();
    if(username == '' || fullname=='' || day=='' || month=='' || year =='' || yahoo == '' || email==''){
        alert('Cac muc co dau * can khai bao day du');
        return false;
    }
    if(!check_email(email)){
        alert('Email khong ton tai');
        $('#email').focus();
        return false;
    }
    if(capcha==''){
        alert('Chua nhap ma bao ve');
        $('#capcha').focus();
        return false;
    }
    website = $('#website').val();
    gavatar = $('input[@name=gavatar]:checked').val();
    skype = $('#skype').val();
    icq = $('#icq').val();
    avatar = $('#avatar').val();
    $.ajax({
        beforeSend: function(){
            $('#cmain').fadeTo('slow',0.09);
        },
        type: "post",
        url: "index.php",
        data: { member: "change_info_ok",
                username: username,
                fullname: fullname,
                brithday: day+'-'+month+'-'+year,
                yahoo: yahoo,
                email: email,
                website: website,
                gavatar: gavatar,
                skype: skype,
                icq: icq,
                avatar: avatar,
                capcha: capcha
        },
        success: function(data){
          err = data.toString();
          if(err=='err'){
            alert('Xac nhan ma bao ve khong dung');
            $('#cmain').fadeTo('fast',1);
            $('#capcha').val('').focus();
            change_capcha();
            return false;
          }
          alert('thay doi thong tin ca nhan thanh cong');
          $('#cmain').fadeTo('slow', 1).html(data);
        }
    });
}

function change_note(){
    content = $('#post-content').val();
    $.ajax({
        beforeSend: function(){
            $('#cmain').fadeTo('slow', 0.09);
        },
        type: "post",
        url: "index.php",
        data: {
            member: "change_note_ok",
            content: content
        },
        success: function(data){
            alert('Cap nhat thanh cong');
            $('#cmain').fadeTo('fast', 1).html(data);
        }
    });
}




function change_tab(div1,div2){
    // div1 : id cua tab dang kich hoat
    // div2 : id cua tab duoc click
    $('#'+div1).removeClass('tab-selected').addClass('tab-unselect');
    $('#'+div2).removeClass('tab-unselect').addClass('tab-selected');
    $('#'+div1+'-box').hide('normal');
    $('#'+div2+'-box').show('normal');
}




function hidepost(){
      $('#post-mode').html('');
      $('#post-content').val('');
      $('#all').removeClass('hide');
      $('#post').removeClass('post-show').addClass('post-hide');
      return false;
}

function del(type,id,mode,dk,div){
    // mode : view khi dang xem bai viet
    // dk : tuy chon nang cao
    // div nang cao
    if(confirm('Ban that su muon xoa bai viet nay?')==true){
        if(dk){
            dat =  type+'=delete&id='+id+'&'+dk;
        }else{
            dat = type+'=delete&id='+id;
        }
        $.ajax({
            type: "post",
            url: "index.php",
            data: dat,
            success: function(data){
                data = data.toString();
                if(data=='error'){
                    alert('Err!');
                    return false;
                }
                if(mode=='view'){
                    viewpages('main',type+'=list');
                }else{
                    if(div){
                        $('#'+div+'-'+id).slideUp('normal');
                    }else{
                        $('#'+type+'-'+id).slideUp('normal');
                    }
                }
            }
        });
    }else{
        return false;
    }
}


function edit(type,id,more,div){
    // more : cac dieu kien mo rong
    // div : div se hien thi ket qua
    if(more){
        dat = type+'=edit&id='+id+"&"+more;
    }else{
        dat = type+'=edit&id='+id;
    }
        $.ajax({
            beforeSend: function(){
                if(div){
                    check = $('#'+div+'_close').val();
                    if(check=='show'){
                        showform(div);
                    }
                    show_loading(div);
                }else{
                    show_loading('main');
                }
            },
            type: "post",
            url: "index.php",
            data: dat,
            success: function(data){
                data = data.toString();
                if(data=='error'){
                    alert('Err!');
                    hide_loading();
                    if(div){
                        $('#'+div).fadeTo('fast',1);
                    }else{
                        $('#main').fadeTo('normal',1);
                    }
                    return false;
                }
                hide_loading();
                if(div){
                    $('#'+div).fadeTo('fast',1).html(data);
                }else{
                    $('#main').fadeTo('normal',1).html(data);
                }
                return false;
            }
        });
}

function search(){
    key = $('#search-key').val();
    type = $('#search-type').val();
    mode = $('#search-mode').val();
    if(key==''){
        alert('Chua nhap tu khoa');
        $('#search-key').focus();
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('main');
        },
        type: "post",
        url: "index.php",
        data: {
                search: 'list',
                type: type,
                key: key,
                mode: mode
        },
        success: function(data){
            hide_loading();
            $('#main').html(data);
        }
    });
}



/// RATE //
function show_star(starNum) {
		remove_star();
		full_star(starNum);
	}

function show_num(starNum){
     document.getElementById('num_star') = starNum;
    }
function full_star(starNum) {
		for (var i=0; i < starNum; i++)
			document.getElementById('star'+ (i+1)).src = RATE_OBJECT_IMG;
	}
function remove_star() {
		for (var i=0; i < 5; i++)
			document.getElementById('star' + (i+1)).src = RATE_OBJECT_IMG_BG; // RATE_OBJECT_IMG_REMOVED;
	}
function show_rating_process() {
		document.getElementById("rating_process").style.display = "block";
        document.getElementById("rate_s").style.display = "none";
	}
function hide_rating_process() {
		document.getElementById("rating_process").style.display = "none";
        document.getElementById("rate_s").style.display = "block";
	}

function rate(type,id,star) {
    $.ajax({
        type: "POST",
        url:  "index.php",
        data: type+"=rate&id="+id+"&star="+star,
        success: function(data){
            $("#"+type+"_star").html(data);
            }
        });
}

function rate_content(div,content){
    $('#'+div).show().html(content);
    return false;
}



function select_weather(img,value){
    $('#weather-thumb').fadeTo('normal',0.09).fadeTo('normal',1).html(img);
    $('#post-weather').val(value);
    return false;
}


function calendar_change_month(type,id){
    $.ajax({
        beforeSend: function(){
            $("#calendar").fadeTo("normal",0.09);
        },
        type: "POST",
        url: "index.php",
        data: "calendar="+type+"&id="+id,
        success: function(data){
             $("#calendar").fadeTo("normal",1).html(data);
        }
    });

}

function memory_add(){
    id = $('#id').val();
    title = $('#title').val();
    content = $('#post-content').val();
    day = $('#day').val();
    month = $('#month').val();
    year = $('#year').val();
    if(title == '' || content == '' || day == '' || month =='' || year == ''){
        alert('Cac muc co dau * can phai nhap');
        return false;
    }
    $.ajax({
        type: "post",
        url: "index.php",
        data: {
            member: "memory_add_ok",
            id: id,
            title: title,
            content: content,
            day: day,
            month: month,
            year: year
        },
        success: function(data){
            $('#cmain').html(data);
            showform('memory_new');
            $('#memory_new').empty();
        }
    });
}



function memory_show_add(){
    check = $('#memory_new_close').val();
    if(check=='show'){
        $.ajax({
            beforeSend: function(){
                show_loading('memory_new');
                showform('memory_new');
            },
            type: "post",
            url: "index.php",
            data: "member=memory_add",
            success: function(data){
                hide_loading();
                $('#memory_new').fadeTo('fast',1).html(data);
            }
        });
    }else{
        showform('memory_new');
        return false;
    }
}

function web_config_check(){
    web_title = $('#web_title').val();
    web_avatar  = $('#web_avatar').val();
    web_language = $('#web_language').val();
    web_template = $('#web_template').val();
    web_timezone = $('#web_timezone').val();
    diary_limit =  $('#diary_limit').val();
    gallery_limit =  $('#gallery_limit').val();
    music_limit =  $('#music_limit').val();
    wish_limit =  $('#wish_limit').val();
    web_badword = $('#badword').val();
    if(diary_limit == '' || gallery_limit == '' || music_limit == '' || wish_limit == ''){
        alert('cac muc co dau * can nhap');
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: {
            admin: "config_ok",
            web_title: web_title,
            web_avatar: web_avatar,
            web_language: web_language,
            web_template: web_template,
            web_timezone: web_timezone,
            diary_limit: diary_limit,
            gallery_limit: gallery_limit,
            music_limit: music_limit,
            wish_limit: wish_limit,
            web_badword: web_badword
        },
        success: function(data){
            err = data.toString();
            if(err=='change_skin'){
                $.ajax({
                beforeSend: function(){
                    $('#all').addClass('hide');
                    hide_loading();
                    $('#loading').slideDown('fast').html("<center>Dang cap nhat giao dien moi vui long doi trong giay lat<br><img src='images/loading.gif'><br></center>");
                },
                type: "post",
                url: "index.php",
                data: "home=home",
                success: function(){
                    document.location.href = "index.php";
                }
                });
                return false;
            }else{
                hide_loading();
                alert('cap nhat thanh cong');
                $('#cmain').fadeTo('fast', 1).html(data);
            }
        }
    });
}


function change_template(name,link){
    if(confirm('Thuc su muon kich hoat giao dien '+name+' thanh giao dien chinh?')==true){
        $.ajax({
            beforeSend: function(){
                $('#all').addClass('hide');
                $('#loading').slideDown('fast').html("<center>Dang cap nhat giao dien moi vui long doi trong giay lat<br><img src='images/loading.gif'><br></center>");
            },
            type: "post",
            url: "index.php",
            data: "member=skin_active&link="+link,
            success: function(data){
                document.location.href = "index.php";
            }
        });
    }else{
        return false;
    }
}

function cat_select_mode(type){
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: "admin=category&type="+type,
        success: function(data){
            hide_loading();
            $('#cmain').fadeTo('fast', 1).html(data);
        }
    });
}

function cat_select_option(type){           // tuy chon cho phan loai khi dang chu de trong admin
     $.ajax({
        beforeSend: function(){
            $('#cat-select').fadeTo('fast', 0.09);
        },
        type: "post",
        url: "index.php",
        data: "admin=category_option&type="+type,
        success: function(data){
            $('#cat-select').fadeTo('fast', 1).html(data);
        }
    });
}


function category_submit(){
    id = $('#id').val();
    title = $('#title').val();
    content = $('#post-content').val();
    subid = $('#category').val();
    cfor = $('#cfor').val();
    order = $('#order').val();
    if(title==''){
        alert('Chua nhap tieu de');
        $('#title').focus();
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: {
            admin: "category_submit",
            id: id,
            cfor: cfor,
            content: content,
            subid: subid,
            title: title,
            order: order
        },
        success: function(data){
            hide_loading();
            $('#cmain').fadeTo('fast', 1).html(data);
        }
    });
}


function block_select_mode(type){
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: "admin=block&type="+type,
        success: function(data){
            hide_loading();
            $('#cmain').fadeTo('fast', 1).html(data);
        }
    });
}

function block_select_code(type){
    $.ajax({
        beforeSend: function(){
            $('#block_function').fadeTo('fast', 0.09);
        },
        type: "post",
        url: "index.php",
        data: "admin=block_code_select&type="+type,
        success: function(data){
            $('#block_function').empty().fadeTo('fast',1).html(data);
        }
    });
}

function block_submit(){
    id = $('#id').val();
    name = $('#name').val();
    showname = $('#showname').val();
    mode = $('#mode').val();
    bfor = $('#for').val();
    bfunction = $('#code').val();
    active = $('input[@name=active]:checked').val();
    border =  $('#order').val();
    if( name=='' || showname == '' || bfunction == ''){
        alert('Cac muc co dau * can xac nhan');
        return false;
    }
    if(bfunction=='no-plugin'){
        alert('Chua co Add-ons nao ban nen chon muc khac');
       // block_select_code(0);
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: {
            admin: "block_submit",
            id: id,
            name: name,
            showname: showname,
            mode: mode,
            bfor: bfor,
            bfunction: bfunction,
            active: active,
            border: border
        },
        success: function(data){
            hide_loading();
            $('#cmain').fadeTo('fast',1).html(data);
        }
    });
}

function active(div,link){
    $.ajax({
        type: "post",
        url: "index.php",
        data: ""+link,
        success: function(data){
            $('#'+div).empty().html(data);
        }
    });
return false;
}

function message_submit(){
    title = $('#title').val();
    content = $('#content').val();
    if(title==''){
        alert('Chua nhap tieu de');
        $('#title').focus();
        return fasle;
    }
    if(content==''){
        alert('Chua nhap noi dung');
        $('#content').focus();
        return false;
    }
    $.ajax({
        beforeSend: function(){
            show_loading('cmain');
        },
        type: "post",
        url: "index.php",
        data: {
            member: "message_submit",
            title: title,
            content: content
        },
        success: function(data){
            hide_loading();
            $('#cmain').fadeTo('fast',1).html(data);
        }
    });
}