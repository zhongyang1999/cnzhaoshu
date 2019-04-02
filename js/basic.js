﻿var isPc=true;var user=null;var page=0;var itemHeight=390;var columns=Math.floor($(window).width()/200);if(columns<5){columns=5}try{$(".wrap").width(columns*200-10);$(".home_treelist").width(columns*200);$(".group_treelist").width((columns-1)*200)}catch(e){}if($("#userlist").length>0){columns--}var pageItems=Math.ceil($(window).height()/itemHeight)*columns;var isLoading=false;var isEnd=false;var qrcodeTicket;var isLogin=false;function urlRequest(name){var match=RegExp("[?&]"+name+"=([^&]*)").exec(window.location.search);return match&&decodeURIComponent(match[1].replace(/\+/g," "))}function valueTrim(id){var value=$(id).val();return value.replace(/(^\s*)|(\s*$)/g,"")}function isPhone(str){return/^1[3-5,7-8]{1}[0-9]{9}$/.test(str)||/^([0-9]{3,4}-)?[0-9]{7,8}$/.test(str)}function showTime(time_str){time_str=time_str.replace(/-/g,"/");var time=new Date(time_str);var now=new Date();var t=now.getTime()-time.getTime();if(t<3600000){t=Math.floor(t/60000);return t==0?"刚刚":t+"分钟前"}else{if(t<86400000){return Math.floor(t/3600000)+"小时前"}else{if(t<2592000000){return Math.floor(t/86400000)+"天前"}else{if(t<31104000000){return Math.floor(t/2592000000)+"月前"}else{return"1年前"}}}}}function mORcm(m){return m<1?Math.round(m*100)+"公分":m+"米"}function addcookie(name,value){var date=new Date();date.setTime(date.getTime()+315360000000);document.cookie=name+"="+escape(value)+";expires="+date.toGMTString()+";path=/;domain=cnzhaoshu.com"}function getcookie(name){var arrStr=document.cookie.split("; ");for(var i=0;i<arrStr.length;i++){var temp=arrStr[i].split("=");if(temp[0]==name){return unescape(temp[1])}}}function delcookie(name){var date=new Date();date.setTime(date.getTime()-10000);document.cookie=name+"=a;expires="+date.toGMTString()+";path=/;domain=cnzhaoshu.com"}function login(){if(window.isOpenLogin){try{XD.sendMessage(parent,'{"event":"login"}')}catch(e){}}else{$.blockUI({message:'<img id="login_qrcode" onclick="$.unblockUI()" src="../img/loging.gif"><div class="login_info">请用 微信 扫描二维码登录<br><label><input type="checkbox" checked="checked">下次自动登录（公用电脑上不要勾选）</label></div>',css:{border:0,top:"110px",left:($(window).width()-280)/2+"px",width:"280px",height:"335px"}});loginQRcode()}}function loginQRcode(){var t=new Date().getTime();$.get("/com/login.qrcode.php?t="+t,function(ticket){qrcodeTicket=ticket;var isIE67=navigator.userAgent.indexOf("MSIE")>0;if(isIE67){$("#login_qrcode").attr("src","/qrlogin/"+qrcodeTicket.toLowerCase()+".jpg")}else{$("#login_qrcode").attr("src","http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket="+ticket)}setTimeout(checkLogin,2000)})}function checkLogin(){var t=new Date().getTime();var isAutoLogin=$(".login_info input[type='checkbox']").is(":checked");$.getJSON("/com/login.checkqrcode.php?t="+t,{ticket:qrcodeTicket,autoLogin:isAutoLogin},function(json){if(json){user=json;$.unblockUI();window.location.reload()}else{setTimeout(checkLogin,2000)}})}function logout(){delcookie("user2");if($("#open_home").length>0){window.location.href=getcookie("open_home2")}else{if(window.location.href.indexOf("findtree.html")>0||window.location.href.indexOf("mytree.html")>0){window.location.href="."}else{window.location.reload()}}}function checkUser(){user=getcookie("user2");if(user){user=JSON.parse(user);$("#welcome").html(user.name);$("#logout").show();$("#login").hide();isLogin=true;if($.inArray(user.role,[101,9])&&window.location.href.indexOf("mytree.html")>0){window.location.replace("/qjd.html?shopid="+user.userid)}}else{user=null;isLogin=false}}function search(forWhere,forLimit,isPriceSlider){if(isLoading){return}isLoading=true;if(window.url_shop_id){forLimit=""}$.getJSON("/com/search.php",{where:forWhere,limit:forLimit,fromWhere:"pc"},function(json){if(json){for(var i=0;i<json.length;i++){var tree=json[i];if(!window.treeid||window.treeid!=tree.treeid){addTree(tree)}}isEnd=json.length<pageItems}else{isEnd=true}var trees=$("#treelist").children();var treelistHeight=Math.ceil(trees.length/columns)*itemHeight+50;$("#treelist").height(treelistHeight);isLoading=false;$("#searchmore").hide();if(window.isOpenApi){try{var top=$("#treelist").offset().top;var height=treelistHeight<$(window).height()-top?$(window).height()-top:treelistHeight;XD.sendMessage(parent,'{"event":"height","height":'+(height+top)+"}")}catch(e){}}});page++;try{setPrice(forWhere,isPriceSlider)}catch(e){}}function addTree(tree){var attribute=[];if(tree.dbh){tree.dbh=parseFloat(tree.dbh);tree.dbh&&attribute.push("径"+tree.dbh+"公分")}if(tree.age){tree.age=parseFloat(tree.dbh);tree.age&&attribute.push("龄"+tree.age+"年")}if(tree.crownwidth){tree.crownwidth=parseFloat(tree.crownwidth);tree.crownwidth&&attribute.push("冠"+mORcm(tree.crownwidth))}if(tree.height){tree.height=parseFloat(tree.height);tree.height&&attribute.push("高"+mORcm(tree.height))}if(tree.branch_point_height){tree.branch_point_height=parseFloat(tree.branch_point_height);tree.branch_point_height&&attribute.push("分"+mORcm(tree.branch_point_height))}if(tree.branch_bough_number){attribute.push("枝"+tree.branch_bough_number+"个")
}if(tree.pname){tree.name="'"+tree.pname+"'"+tree.name}tree.price=Math.round(tree.price*100)/100;if(tree.price==0){tree.price="议价"}if(!tree.unit){tree.unit="株"}tree.imgpath=decodeImgpath(tree.imgpath);var renzheng="";switch(tree.state){case 2:renzheng='<span onmouseover="showHintRenZheng(this)" class="renzheng">已认证</span>';break;case 3:renzheng='<span onmouseover="showHintQiJianDian(this)" class="qjd">旗舰店</span>';break;case 4:renzheng='<span onmouseover="showHintQiJianDian(this)" class="qjd">单品王</span>';break}var collections=tree.collections>0?' <span class="cangmi">藏米'+tree.collections+"</span>":"";var html='<div class="imgbox">';html+='<img src="/trees/s2/'+tree.imgpath[0]+'" /></div>';if(tree.invoice){html+='<img class="fapiao" src="/img/fapiao.png?t=2">'}if(tree.userstate){html+='<img style="position: absolute; width: 70px; height:70px; border-radius: 50%; margin: -210px 0 0 10px;" onerror="this.parentNode.removeChild(this);" src="/platform/'+tree.userstate+'.png">'}if(tree.phototime){html+='<p><div class="poi"></div></p>'}html+='<p><span class="name">'+tree.name+"</span></p>";if(tree.type==101){html+='<p><span class="place">'+showTime(tree.time)+"</span></p>";html+='<p class="size">'+tree.remark+"</p>"}else{html+='<p><span class="price"><i>￥</i>'+tree.price+'</span> <span class="count">'+tree.count+tree.unit+"</span></p>";html+='<p><span class="place">'+showTime(tree.time)+"</span></p>";html+='<p class="size">'+attribute.join(" ")+"</p>"}html+="<p>"+tree.username+"</p>";html+='<p><span class="place">'+tree.province+" "+tree.district+" "+collections+renzheng+"</span></p>";var href="tree.html?t=1&tree="+JSON.stringify(tree);if(window.location.href.indexOf("findtree.html")>0){href+="&type=findtree"}if(window.isOpenApi){var login=window.isOpenLogin?"&login=1":"";$("#treelist").append($("<a/>").html(html).attr({"href":href+login}))}else{$("#treelist").append($("<a/>").html(html).attr({"href":href,"target":"_blank"}))}}function showHintRenZheng(target){var node=$(target).on("mouseout",function(){$(".hintRenZheng").remove()}).parent().parent().parent();$("<div></div>").html("已实地考察<br>认证费1500").addClass("hintRenZheng").css({left:"0px",bottom:"60px"}).appendTo(node)}function showHintQiJianDian(target){var node=$(target).on("mouseout",function(){$(".hintQiJianDian").remove()}).parent().parent().parent();$("<div></div>").html("有行业资质和规模经营<br>年服务费1900元").addClass("hintQiJianDian").css({left:"0px",bottom:"60px"}).appendTo(node)}function onSearch(key,isExact){if(!key){key=valueTrim("#key")}if(!key){return}if(!window.isHome){var href=isExact?'where={"key":"'+key+'","exact":1}':"key="+key;if(window.isOpenApi){var open_home=getcookie("open_home2");if(open_home){location.href=open_home+(open_home.indexOf("platform")>0?"&":"?")+href}else{location.href="./?"+href}}else{location.href="./?"+href}return}$("#where").html("");$("#spec_dbh").val("");$("#spec_crownwidth").val("");$("#spec_height").val("");$("#btn_price").html("价格◇");$("#btn_price").attr("class","unselect");$("#btn_time").attr("class","unselect");$("#btn_renzheng").attr("class","unselect");$("#btn_yuantu").attr("class","unselect");$("#btn_shipin").attr("class","unselect");$("#group1000").attr("class","unselect");$("#group1001").attr("class","unselect");searchKey(key,null,isExact);if($("#tree").is(":hidden")){showTree()}}function setHotkey(){if($("#hotkey").length){var html="<a href=\"javascript:onSearch('张辉')\">搜苗圃</a><a href=\"javascript:onSearch('13931398852')\">搜手机</a>";var hotkeys="国槐,白腊,法桐,五角枫,油松,银杏,海棠,白皮松,紫叶李,金叶榆";hotkeys=hotkeys.split(",");for(var i=0;i<hotkeys.length;i++){html+="<a href=\"javascript:onSearch('"+hotkeys[i]+"')\">"+hotkeys[i]+"</a>"}$("#hotkey").html(html)}}function setCart(){if(user&&$("#cart").length){$.get("/com/count.cart.php",{userid:user.userid},function(count){$("#cart").html(count).animate({fontSize:"110px",lineHeight:"0"}).animate({fontSize:"32px",lineHeight:"80%"}).click(function(){window.location="findtree.html"})})}}function setOpenApi(){if(urlRequest("platform")){window.platform=urlRequest("platform")}if(urlRequest("union")){window.union=urlRequest("union")}if(urlRequest("name")){document.title=urlRequest("name")}else{if(window.platform){switch(parseInt(platform)){case 1000:document.title="北京苗木";break;case 1001:document.title="北京市园林绿化行业协会";break}}}if(urlRequest("login")){window.isOpenLogin=true}else{$(".login").show()}if(window.isHome){addcookie("open_home2",window.location.href)}else{$("#open_home").attr("href",getcookie("open_home2"))}var login=window.isOpenLogin?"?login=1":"";$("#open_mytree").attr("href","mytree.html"+login);$("#open_findtree").attr("href","findtree.html"+login);var callback_message=function(data){if(typeof data=="string"){data=JSON.parse(data);switch(data.event){case"search":if(isEnd||isLoading||$("#treeview").length>0||(!isPc&&$("#userlist").length>0&&$("#userlist").css("display")!="none")){return}$("#searchmore").show();search(getWhere(),getLimit());break;case"key":onSearch(data.key);break}}};XD.receiveMessage(callback_message)
}checkUser();setCart();if(window.isOpenApi){setOpenApi()}else{setHotkey()}$("#key").keydown(function(e){if(e.keyCode==13){onSearch()}});