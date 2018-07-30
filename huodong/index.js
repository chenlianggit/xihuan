// yuan.js 封装常用方法，提高javascript面向对象编程。 2017年05月19日
var version = '0.5.19';
(function () {
  if( !window.yuan){
    window.yuan = new Object(),
    window.y = new Object();
  }
  //api
  window.yuan = {
    //String
      //tirm > 去除字符串 > 使用：方法接受两个参数，第一个是字符串，第二个是否全局搜索；
        //默认：只去掉左右两侧空格，
        //参数：g => 去掉全部空格
      tirm : function(){
        if( typeof arguments[0] != 'string'){
          console.warn('tirm的参数不是string类型');
          return;
        }
        if ( arguments.length === 1){
          return arguments[0].replace(/(^\s*) | (\s*$)/,'');
        }else if( arguments.length >1 && arguments[1] === 'g') {
          return arguments[0].replace(/\s+/g,'');
        }
      },
      //getUriparams > 获取get请求参数
        //参数： null
      getUriparams : function(){
        var WindowSearch = window.location.search;//获取参数
        if( !WindowSearch){console.warn('getUrlparams方法找不到url的参数'); return;}
        var obj = {},newArr = [];
        var arr = WindowSearch.substring(1).split('&');
        for(var i = arr.length; i-- ; ){
         newArr = arr[i].split('=');
          obj[newArr[0]]= newArr[1];
        }
        return obj;
      },
      encode: function(s){
        return encodeURIComponent(s);
      },
    //String - end

      // Date
        // getDateStr > 获取当前日期 >
          //默认: 0 获取今天
          //参数： number -1：上一天  0：今天  1：下一天
          getDateStr : function(AddDateCount,ConnectType){
            AddDateCount = AddDateCount ? AddDateCount : 0;
            ConnectType = arguments.length < 1 ? '-' : ConnectType;
            var dd = new Date(); // 获取当前时间
                dd.setDate( dd.getDate() + AddDateCount);
            var y = dd.getFullYear(); //获取年
            var oddM = dd.getMonth()+1;
            var m = oddM.toString().length == 1 ? '0' + oddM : oddM;
            var d = dd.getDate(); //获取日
            return y+ConnectType+m+ConnectType+d;
          },

      // dubug
        //jsType > 查看类型
        jsType : function (){
            if ( !arguments.length){
                console.warn('jsTpye参数不能为空'); 
                return null;
            }
            if ( arguments[0] === null){
                console.warn('类型为:null'); 
                return null;
            }
            var type = typeof arguments[0];
            switch (type) {
              case 'number':
                  return 'number'; break;
              case 'string':
                  return 'string'; break;
              case 'boolean':
                return 'boolean'; break;
              case 'function':
                return 'function'; break;
              case 'undefined':
                return 'undefined'; break;
              case 'null' :
                  return 'null'; break;
                break;
              case 'object':
                if ( Object.prototype.toString.apply( arguments[0]) === '[object Array]') {
                  return 'array' ; return ;
                }else {
                 return 'object' ; return ;
                };
                break;
            }
        },
        //isFunction > 判断是否func
      isFunction: function(arg){
            return typeof arguments[0] === 'function' ? true : false;
      },
     //cookie
      //expires 设置过期时间
      //path 定义cookie有效路局
      //domain 创建cookie的网页所拥有的域名
      //secure cookie的传输需要使用安全协议
        cookie: function(key, value, options){    
         if ( arguments.length > 2 && !yuan.isFunction(value)){
            var date = new Date(); 
            var expires = options.expires + '';
            if (expires.indexOf('h') != -1){ //小时
             var expiresHour = expires.replace('h','');
             date.setTime(Number(expiresHour)*3600*1000);
            }else{
                var date = new Date();
                var expiresDays;
                if( expires.indexOf('d') == -1){
                    expiresDays = options.expires;
                }else {
                    expiresDays = expires.indexOf('d') != -1 ? expires.replace('d','') : expires;
                }
                date.setTime(Number(expiresDays)*24*3600*1000);
            }
             
            //设置cookie
            return ( document.cookie = [
                 yuan.encode(key), '=', yuan.encode(value),
                 options.expires ?  '; expires=' + date.toUTCString() : '',
                 options.path ?  '; path=' + options.path : '',
                 options.domain ? '; domain=' + options.domain : '',
                 options.secure ? '; secure=' + options.secure : false
             ].join(''));
        }



    },
  }

})();
