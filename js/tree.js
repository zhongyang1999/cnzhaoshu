var tree,attribute_name={5:"\u80f8\u5f84",6:"\u5730\u5f84",7:"\u76c6\u5f84",10:"\u682a\u9ad8",11:"\u6761\u957f",12:"\u4e3b\u8513\u957f",17:"\u682a\u9ad8"};function mORcm(a){return 1>a?Math.round(100*a)+"\u516c\u5206":a+"\u7c73"}
function setTree(){tree=JSON.parse(urlRequest("tree"));$("#imgbox").html('<img src="/trees/b2/'+tree.imgpath[0]+'">');tree.invoice&&$("#imgbox").append('<img class="fapiao" src="/img/fapiao.png?t=2">');var a="";switch(tree.state){case 2:a='<span class="renzheng">\u5df2\u8ba4\u8bc1</span>';break;case 3:a='<span class="qjd">\u65d7\u8230\u5e97</span>';break;case 4:a='<span class="qjd">\u5355\u54c1\u738b</span>'}a='<p><b class="name">'+tree.name+"</b>"+('<a class="poi" target="_blank" href="http://apis.map.qq.com/uri/v1/marker?marker=coord:'+
tree.x+","+tree.y+";title:"+tree.username+" "+tree.name+'&referer=geo"> </a>')+(0<1*tree.collections?' <span class="cangmi">\u85cf\u7c73'+tree.collections+"</span>":"")+a+"</p>";tree.ldname&&(a+='<p class="ldname">'+tree.ldname+"</p>");a+="<table>";101!=tree.type&&(a+='<tr><td class="field">\u4e0a\u8f66\u5355\u4ef7\uff1a</td><td class="price">'+tree.price+"</td></tr>");a=tree.qrcodeid?a+('<tr><td class="field">\u6811\u724c\uff1a</td><td>'+tree.qrcodeid+"</td>"):a+('<tr><td class="field">\u7f16\u53f7\uff1a</td><td>'+
tree.treeid+"</td>");tree.dbh&&(a+='<tr><td class="field">'+(tree.dbh_type?attribute_name[tree.dbh_type]:"\u80f8\u5f84")+"\uff1a</td><td>"+tree.dbh+"\u516c\u5206</td>");tree.age&&(a+='<tr><td class="field">\u82d7\u9f84\uff1a</td><td>'+tree.age+"\u5e74</td>");tree.crownwidth&&(a+='<tr><td class="field">\u51a0\u5e45\uff1a</td><td>'+mORcm(tree.crownwidth)+"</td>");tree.height&&(a+='<tr><td class="field">'+(tree.height_type?attribute_name[tree.height_type]:"\u682a\u9ad8")+"\uff1a</td><td>"+mORcm(tree.height)+
"</td>");tree.branch_point_height&&(a+='<tr><td class="field">\u5206\u679d\u70b9\u9ad8\uff1a</td><td>'+mORcm(tree.branch_point_height)+"</td>");tree.branch_bough_number&&(a+='<tr><td class="field">\u5206\u679d\u6570\uff1a</td><td>'+tree.branch_bough_number+"\u4e2a</td>");tree.substrate&&(a+='<tr><td class="field">\u57fa\u8d28\uff1a</td><td>'+tree.substrate+"</td>");tree.remark&&(a+='<tr><td class="field">\u5907\u6ce8\uff1a</td><td>'+tree.remark+"</td>");101!=tree.type&&(a+='<tr><td class="field">\u6570\u91cf\uff1a</td><td>'+
tree.count+tree.unit+"</td>");a+='<tr><td class="field">\u4f4d\u7f6e\uff1a</td><td>'+tree.province+" "+tree.district+"</td>";a+='<tr><td class="field">\u82d7\u5546\uff1a</td><td>'+tree.username+"</td>";a+='<tr><td class="field">\u8054\u7cfb\u7535\u8bdd\uff1a</td><td><a href="tel:'+tree.userphone+'">'+tree.userphone+"</a></td>";a+='<tr><td class="field">\u53d1\u5e03\u65f6\u95f4\uff1a</td><td>'+tree.time+"</td>";a+="</table>";$("#treeinfo").html(a);var b=tree.phototime;b&&(b=b.split(";"));var d=tree.photogps;
d&&(d=d.split(";"));for(var a="",c=0;c<tree.imgpath.length;c++)b&&b[c]&&(a+='<div style="text-align:center;">'),a+='<img onload="resetHeight()" src="/trees/o2/'+tree.imgpath[c]+'" />',b&&b[c]&&(a+='<br><a target="_blank" class="poi2" href="http://apis.map.qq.com/uri/v1/marker?marker=coord:'+d[c]+";title:"+tree.username+" "+tree.name+'&key=CNPBZ-SM4WD-UHP4G-PSOWR-Z7ZVO-YKFJU&referer=zhaoshu"></a><br><span class="phototime">'+b[c]+"</span></div>");$("#imglist").append(a);user&&"findtree"==urlRequest("type")?
($("#btnForCollect").html("\u79fb\u51fa\u627e\u6811\u8f66"),document.getElementById("btnForCollect").onclick=deleteMyTree):($("#btnForCollect").html("\u52a0\u5165\u627e\u6811\u8f66"),document.getElementById("btnForCollect").onclick=addMyTree);document.getElementById("btnGotoShop").onclick=function(){0<=$.inArray(tree.userrole,[101,9])?window.open("/qjd.html?shopid="+tree.userid):window.open("/?shop="+tree.shopid+"&shopname="+tree.username)};user&&user.shopid==tree.shopid||$.post("/com/accesstree.php",
{type:1,treeid:tree.treeid,shopid:tree.shopid,userid:user?user.userid:0})}function resetHeight(){$("#content").height($("#imglist").height()+500)}function top5(){var a={};a.key=tree.name;tree.dbh&&(a.dbh=tree.dbh);tree.age&&(a.age=tree.age);tree.crownwidth&&(a.crownwidth=tree.crownwidth);tree.height&&(a.height=tree.height);search(JSON.stringify(a),"0,6")}
function addMyTree(){user?$.get("/com/addmytree.php?userid="+user.userid+"&treeid="+tree.treeid+"&shopid="+tree.userid+(user.userid==tree.userid?"&isOwn=1":""),function(a){a&&($("#cart").html(1*$("#cart").html()+1),$("#cart").animate({fontSize:"110px",lineHeight:"0"}).animate({fontSize:"32px",lineHeight:"80%"}));$("#btnForCollect").html("\u79fb\u51fa\u627e\u6811\u8f66");document.getElementById("btnForCollect").onclick=deleteMyTree}):login()}
function deleteMyTree(){$.get("/com/deletemytree.php?userid="+user.userid+"&treeid="+tree.treeid+(user.userid==tree.userid?"&isOwn=1":""),function(a){$("#cart").html(1*$("#cart").html()-1);$("#cart").animate({fontSize:"110px",lineHeight:"0"}).animate({fontSize:"32px",lineHeight:"80%"});$("#btnForCollect").html("\u52a0\u5165\u627e\u6811\u8f66");document.getElementById("btnForCollect").onclick=addMyTree})}setTree();top5();