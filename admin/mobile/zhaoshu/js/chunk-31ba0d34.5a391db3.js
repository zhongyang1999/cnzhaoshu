(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-31ba0d34"],{"068e":function(t,e,n){"use strict";var r=n("f4a6"),a=n.n(r);a.a},"65f3":function(t,e,n){"use strict";n.r(e);var r=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"contract-tree-warp"},[t._m(0),r("div",{staticClass:"contract-tree-projectName"},[t._m(1),r("div",{staticClass:"contract-tree-projectName-right"},[r("div",{staticClass:"contract-tree-projectNumber"},[t._v("\n                合同号: "+t._s(t.treeInfo.order_num)+"\n            ")]),r("div",{staticClass:"contract-tree-projectNa"},[t._v("\n                "+t._s(t.treeInfo.name)+"\n            ")]),r("div",{staticClass:"contract-tree-projectNum"},[r("span",[t._v("采购数量:"),r("span",[t._v(t._s(t.treeInfo.sum))]),t._v("株")])])])]),r("van-list",{attrs:{finished:t.finished,"finished-text":2==t.yc?"没有更多异常":"全部加载，没有更多数据"},on:{load:t.getMoreTree},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("div",{staticClass:"contract-tree-content"},t._l(t.treeInfo.content,function(e,a){return r("div",{key:e.qrcode,staticClass:"contract-tree-one",on:{click:function(e){t.linkTreeInfo(a)}}},[r("div",{staticClass:"contract-tree-one-name"},[r("div",{staticClass:"contract-tree-one-tree"},[r("div",{staticClass:"name",domProps:{textContent:t._s(e.tree_name)}}),r("div",{staticClass:"num"},[r("span",[t._v("1")]),t._v("株\n                        ")]),r("div",{staticClass:"status",domProps:{textContent:t._s(e.order_state)}})]),r("div",{staticClass:"contract-tree-one-code"},[r("img",{attrs:{src:n("c63a"),alt:""}}),r("span",{domProps:{textContent:t._s(e.qrcode)}})])]),r("div",{staticClass:"contract-tree-one-content"},[r("span",{directives:[{name:"show",rawName:"v-show",value:e.dbh,expression:"tree.dbh"}],domProps:{textContent:t._s("胸径"+e.dbh+"公分")}}),r("span",{directives:[{name:"show",rawName:"v-show",value:e.plant_height,expression:"tree.plant_height"}],domProps:{textContent:t._s("株高"+e.plant_height+"米")}}),r("span",{directives:[{name:"show",rawName:"v-show",value:e.crown,expression:"tree.crown"}],domProps:{textContent:t._s("冠幅"+e.crown+"公分")}})]),r("div",{staticClass:"contract-tree-one-status"},[r("img",{directives:[{name:"show",rawName:"v-show",value:"1"==e.unusual_state,expression:"tree.unusual_state == '1'"}],attrs:{src:n("6ace"),alt:""}}),r("img",{directives:[{name:"show",rawName:"v-show",value:"2"==e.unusual_state,expression:"tree.unusual_state == '2'"}],attrs:{src:n("6874"),alt:""}})])])}))])],1)},a=[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"contract-tree-header"},[r("img",{attrs:{src:n("e6a7"),alt:""}})])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"contract-tree-projectName-left"},[r("img",{attrs:{src:n("e8e0"),alt:""}})])}],s=(n("cadf"),n("551c"),n("097d"),n("fc62")),i={data:function(){return{tender_order_id:"",treeInfo:{},page:0,loading:!1,finished:!1}},created:function(){this.tender_order_id=this.$route.params.id,this.yc=this.$route.params.yc},methods:{linkTreeInfo:function(t){"未起树"!=this.treeInfo.content[t].order_state?this.$router.push({name:"treeInfo",params:{id:this.treeInfo.content[t].maps_order_id}}):this.Dialog.alert({message:"还未起树，无监管信息！"}).then(function(){})},getMoreTree:function(){this.page=this.page+1,this.getTreeList()},linkTreeSupervise:function(t){alert(t)},getTreeList:function(){var t=this,e={tender_order_id:this.tender_order_id,page:this.page,state:2==this.yc?"7":"0"};Object(s["d"])(e).then(function(e){if(1==t.page)t.treeInfo=e.data.data,t.loading=!1;else{var n=t.treeInfo.content;n=n.concat(e.data.data.content),t.$set(t.treeInfo,"content",n),t.loading=!1}0==e.data.data.content.length&&(t.finished=!0)})}}},o=i,c=(n("068e"),n("2877")),d=Object(c["a"])(o,r,a,!1,null,null,null);d.options.__file="treeSupervise.vue";e["default"]=d.exports},6874:function(t,e,n){t.exports=n.p+"img/waring@2x.8dc3a1ef.png"},"6ace":function(t,e,n){t.exports=n.p+"img/finish-shen@2x.3e1aab53.png"},c63a:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpBNTFERUFFOEYyMEQxMUU4QkY2Nzk5QkM4Q0NGNUVGQSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpBNTFERUFFOUYyMEQxMUU4QkY2Nzk5QkM4Q0NGNUVGQSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkE1MURFQUU2RjIwRDExRThCRjY3OTlCQzhDQ0Y1RUZBIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkE1MURFQUU3RjIwRDExRThCRjY3OTlCQzhDQ0Y1RUZBIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+gpZZEAAAArNJREFUeNqklL9rFEEUx3dv9/a8GBA9MRq1EH8Vkk4IShoJFiqonYKCf4LYWCpqmcpSDjFFEiM2FoIEQxARLfyBFhpUVCwUPDTEwx9x73bXz9u8wcm6u4UOfHfmzbz5znfmvbdus9kciqLoiuu6W5z89hmc7Ha7E/V63cGvJwzDceYOO8Xtlh/H8W2cl2FM6qRrOcRgDxj3fX8a31aSJBeFlD0zjL9n/BPQAPt8PkJ6BlwoOP0AuAk2oLpFPwSpzA/nOXOY3OpRRe16ybUaqfQ47qTXcd2vZkEP+HO9OBbiXuE3xF4JccW6pt0vIRdSi6tScf6zZVVn1fxzM0orGY3G+lKy96MGpap2rx2oHPUSg1++RvEQxtO8dGPtuPigqK2q5sQGJxi37fjgG4F1rO3wPc87y3XOMT9VdFUIx2q12hvSzaGYTjF9F4yWvPtPIT7P5usYfVoQdhM1c+CZCRJqnnDQZvbsYixpGllPA523AB5K5ckJs0zOFkVdfChjhw0Oyp1Op/OJpRtFGSFPJZW3msEl+p2q2I6IrL+SfwVP8FyIIZO4DNIv/yvFiIGQ4vtDFM8wNwDea+CWBA/HYUgeACnpNvY25u8VKTW3lNMHGIxgn86mj7aj4CprW/F7XK1W33LAMS3dEMzrLUVQQ94Z5S98PWmhrLjkw/XSwPKXE98Jo8wWkyr1fYlJwxRIrYTYrMVBEMg7roBso86NqNpkUV9yBAFCvKi46J2yKS1rPMMk/V7sAEyDfg1ySHBfkjE97P1WSJwlZxxLgdCvB57m+JQpLOtZZD00xO3Mr89WPm9STzZb/+Mk739M+wA6hng3k2syqZY+GpsH1Qz1v7LSOng/9nZTfYwTYrCKtU1CfA0cZOJOllgLpp8N93m/11IACHjHxj7GAf1lsDanWlu/BRgAu6peM+Ep/qwAAAAASUVORK5CYII="},e6a7:function(t,e,n){t.exports=n.p+"img/bg-treelist@2x.e5100e31.png"},e8e0:function(t,e,n){t.exports=n.p+"img/mbetree-mg@2x.efeea352.png"},f4a6:function(t,e,n){},fc62:function(t,e,n){"use strict";n.d(e,"b",function(){return s}),n.d(e,"a",function(){return i}),n.d(e,"d",function(){return o}),n.d(e,"e",function(){return c}),n.d(e,"f",function(){return d}),n.d(e,"j",function(){return u}),n.d(e,"c",function(){return p}),n.d(e,"h",function(){return l}),n.d(e,"g",function(){return m}),n.d(e,"i",function(){return h});n("cadf"),n("551c"),n("097d");var r=n("66df"),a=n("c276"),s=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/project_list.php",data:e,method:"post"})},i=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/project_info.php",data:e,method:"post"})},o=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/tree_list.php",data:e,method:"post"})},c=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/maps_record_list.php",data:e,method:"post"})},d=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/search_my_maps.php",method:"post",data:e})},u=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/update_tree_maps.php",method:"post",data:e})},p=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/check_maps_order.php",method:"post",data:e})},l=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/maps_record_insert.php",method:"post",data:e})},m=function(t){return r["a"].request({url:"admin/supervise/maps_uploads.php",method:"post",data:t})},h=function(t){var e=Object(a["a"])(t);return r["a"].request({url:"admin/supervise/update_record_state.php",method:"post",data:e})}}}]);
//# sourceMappingURL=chunk-31ba0d34.5a391db3.js.map