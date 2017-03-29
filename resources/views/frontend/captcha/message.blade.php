@if (!1)
<script>
@endif
/**
 *     __  ___
 *    /  |/  /___   _____ _____ ___   ____   ____ _ ___   _____
 *   / /|_/ // _ \ / ___// ___// _ \ / __ \ / __ `// _ \ / ___/
 *  / /  / //  __/(__  )(__  )/  __// / / // /_/ //  __// /
 * /_/  /_/ \___//____//____/ \___//_/ /_/ \__, / \___//_/
 *                                        /____/
 *
 * @description MessengerJS, a common cross-document communicate solution.
 * @author biqing kwok
 * @version 2.0
 * @license release under MIT license
 */

window.Messenger = (function(){

    // 消息前缀, 建议使用自己的项目名, 避免多项目之间的冲突
    // !注意 消息前缀应使用字符串类型
    var prefix = "[PROJECT_NAME]",
        supportPostMessage = 'postMessage' in window;

    // Target 类, 消息对象
    function Target(target, name, prefix){
        var errMsg = '';
        if(arguments.length < 2){
            errMsg = 'target error - target and name are both required';
        } else if (typeof target != 'object'){
            errMsg = 'target error - target itself must be window object';
        } else if (typeof name != 'string'){
            errMsg = 'target error - target name must be string type';
        }
        if(errMsg){
            throw new Error(errMsg);
        }
        this.target = target;
        this.name = name;
        this.prefix = prefix;
    }

    // 往 target 发送消息, 出于安全考虑, 发送消息会带上前缀
    if ( supportPostMessage ){
        // IE8+ 以及现代浏览器支持
        Target.prototype.send = function(msg){
            this.target.postMessage(this.prefix + '|' + this.name + '__Messenger__' + msg, '*');
        };
    } else {
        // 兼容IE 6/7
        Target.prototype.send = function(msg){
            var targetFunc = window.navigator[this.prefix + this.name];
            if ( typeof targetFunc == 'function' ) {
                targetFunc(this.prefix + msg, window);
            } else {
                throw new Error("target callback function is not defined");
            }
        };
    }

    // 信使类
    // 创建Messenger实例时指定, 必须指定Messenger的名字, (可选)指定项目名, 以避免Mashup类应用中的冲突
    // !注意: 父子页面中projectName必须保持一致, 否则无法匹配
    function Messenger(messengerName, projectName){
        this.targets = {};
        this.name = messengerName;
        this.listenFunc = [];
        this.prefix = projectName || prefix;
        this.initListen();
    }

    // 添加一个消息对象
    Messenger.prototype.addTarget = function(target, name){
        var targetObj = new Target(target, name,  this.prefix);
        this.targets[name] = targetObj;
    };

    // 初始化消息监听
    Messenger.prototype.initListen = function(){
        var self = this;
        var generalCallback = function(msg){
            if(typeof msg == 'object' && msg.data){
                msg = msg.data;
            }
            
            var msgPairs = msg.split('__Messenger__');
            var msg = msgPairs[1];
            var pairs = msgPairs[0].split('|');
            var prefix = pairs[0];
            var name = pairs[1];

            for(var i = 0; i < self.listenFunc.length; i++){
                if (prefix + name === self.prefix + self.name) {
                    self.listenFunc[i](msg);
                }
            }
        };

        if ( supportPostMessage ){
            if ( 'addEventListener' in document ) {
                window.addEventListener('message', generalCallback, false);
            } else if ( 'attachEvent' in document ) {
                window.attachEvent('onmessage', generalCallback);
            }
        } else {
            // 兼容IE 6/7
            window.navigator[this.prefix + this.name] = generalCallback;
        }
    };

    // 监听消息
    Messenger.prototype.listen = function(callback){
        var i = 0;
        var len = this.listenFunc.length;
        var cbIsExist = false;
        for (; i < len; i++) {
            if (this.listenFunc[i] == callback) {
                cbIsExist = true;
                break;
            }
        }
        if (!cbIsExist) {
            this.listenFunc.push(callback);
        }
    };
    // 注销监听
    Messenger.prototype.clear = function(){
        this.listenFunc = [];
    };
    // 广播消息
    Messenger.prototype.send = function(msg){
        var targets = this.targets,
            target;
        for(target in targets){
            if(targets.hasOwnProperty(target)){
                targets[target].send(msg);
            }
        }
    };

    return Messenger;
})();

(function(){function N(p,r){function q(a){if(q[a]!==w)return q[a];var c;if("bug-string-char-index"==a)c="a"!="a"[0];else if("json"==a)c=q("json-stringify")&&q("json-parse");else{var e;if("json-stringify"==a){c=r.stringify;var b="function"==typeof c&&s;if(b){(e=function(){return 1}).toJSON=e;try{b="0"===c(0)&&"0"===c(new t)&&'""'==c(new A)&&c(u)===w&&c(w)===w&&c()===w&&"1"===c(e)&&"[1]"==c([e])&&"[null]"==c([w])&&"null"==c(null)&&"[null,null,null]"==c([w,u,null])&&'{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}'==
c({a:[e,!0,!1,null,"\x00\b\n\f\r\t"]})&&"1"===c(null,e)&&"[\n 1,\n 2\n]"==c([1,2],null,1)&&'"-271821-04-20T00:00:00.000Z"'==c(new C(-864E13))&&'"+275760-09-13T00:00:00.000Z"'==c(new C(864E13))&&'"-000001-01-01T00:00:00.000Z"'==c(new C(-621987552E5))&&'"1969-12-31T23:59:59.999Z"'==c(new C(-1))}catch(f){b=!1}}c=b}if("json-parse"==a){c=r.parse;if("function"==typeof c)try{if(0===c("0")&&!c(!1)){e=c('{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}');var n=5==e.a.length&&1===e.a[0];if(n){try{n=!c('"\t"')}catch(d){}if(n)try{n=
1!==c("01")}catch(g){}if(n)try{n=1!==c("1.")}catch(m){}}}}catch(X){n=!1}c=n}}return q[a]=!!c}p||(p=k.Object());r||(r=k.Object());var t=p.Number||k.Number,A=p.String||k.String,H=p.Object||k.Object,C=p.Date||k.Date,G=p.SyntaxError||k.SyntaxError,K=p.TypeError||k.TypeError,L=p.Math||k.Math,I=p.JSON||k.JSON;"object"==typeof I&&I&&(r.stringify=I.stringify,r.parse=I.parse);var H=H.prototype,u=H.toString,v,B,w,s=new C(-0xc782b5b800cec);try{s=-109252==s.getUTCFullYear()&&0===s.getUTCMonth()&&1===s.getUTCDate()&&
10==s.getUTCHours()&&37==s.getUTCMinutes()&&6==s.getUTCSeconds()&&708==s.getUTCMilliseconds()}catch(Q){}if(!q("json")){var D=q("bug-string-char-index");if(!s)var x=L.floor,M=[0,31,59,90,120,151,181,212,243,273,304,334],E=function(a,c){return M[c]+365*(a-1970)+x((a-1969+(c=+(1<c)))/4)-x((a-1901+c)/100)+x((a-1601+c)/400)};(v=H.hasOwnProperty)||(v=function(a){var c={},e;(c.__proto__=null,c.__proto__={toString:1},c).toString!=u?v=function(a){var c=this.__proto__;a=a in(this.__proto__=null,this);this.__proto__=
c;return a}:(e=c.constructor,v=function(a){var c=(this.constructor||e).prototype;return a in this&&!(a in c&&this[a]===c[a])});c=null;return v.call(this,a)});B=function(a,c){var e=0,b,f,n;(b=function(){this.valueOf=0}).prototype.valueOf=0;f=new b;for(n in f)v.call(f,n)&&e++;b=f=null;e?B=2==e?function(a,c){var e={},b="[object Function]"==u.call(a),f;for(f in a)b&&"prototype"==f||v.call(e,f)||!(e[f]=1)||!v.call(a,f)||c(f)}:function(a,c){var e="[object Function]"==u.call(a),b,f;for(b in a)e&&"prototype"==
b||!v.call(a,b)||(f="constructor"===b)||c(b);(f||v.call(a,b="constructor"))&&c(b)}:(f="valueOf toString toLocaleString propertyIsEnumerable isPrototypeOf hasOwnProperty constructor".split(" "),B=function(a,c){var e="[object Function]"==u.call(a),b,h=!e&&"function"!=typeof a.constructor&&F[typeof a.hasOwnProperty]&&a.hasOwnProperty||v;for(b in a)e&&"prototype"==b||!h.call(a,b)||c(b);for(e=f.length;b=f[--e];h.call(a,b)&&c(b));});return B(a,c)};if(!q("json-stringify")){var U={92:"\\\\",34:'\\"',8:"\\b",
12:"\\f",10:"\\n",13:"\\r",9:"\\t"},y=function(a,c){return("000000"+(c||0)).slice(-a)},R=function(a){for(var c='"',b=0,h=a.length,f=!D||10<h,n=f&&(D?a.split(""):a);b<h;b++){var d=a.charCodeAt(b);switch(d){case 8:case 9:case 10:case 12:case 13:case 34:case 92:c+=U[d];break;default:if(32>d){c+="\\u00"+y(2,d.toString(16));break}c+=f?n[b]:a.charAt(b)}}return c+'"'},O=function(a,c,b,h,f,n,d){var g,m,k,l,p,r,s,t,q;try{g=c[a]}catch(z){}if("object"==typeof g&&g)if(m=u.call(g),"[object Date]"!=m||v.call(g,
"toJSON"))"function"==typeof g.toJSON&&("[object Number]"!=m&&"[object String]"!=m&&"[object Array]"!=m||v.call(g,"toJSON"))&&(g=g.toJSON(a));else if(g>-1/0&&g<1/0){if(E){l=x(g/864E5);for(m=x(l/365.2425)+1970-1;E(m+1,0)<=l;m++);for(k=x((l-E(m,0))/30.42);E(m,k+1)<=l;k++);l=1+l-E(m,k);p=(g%864E5+864E5)%864E5;r=x(p/36E5)%24;s=x(p/6E4)%60;t=x(p/1E3)%60;p%=1E3}else m=g.getUTCFullYear(),k=g.getUTCMonth(),l=g.getUTCDate(),r=g.getUTCHours(),s=g.getUTCMinutes(),t=g.getUTCSeconds(),p=g.getUTCMilliseconds();
g=(0>=m||1E4<=m?(0>m?"-":"+")+y(6,0>m?-m:m):y(4,m))+"-"+y(2,k+1)+"-"+y(2,l)+"T"+y(2,r)+":"+y(2,s)+":"+y(2,t)+"."+y(3,p)+"Z"}else g=null;b&&(g=b.call(c,a,g));if(null===g)return"null";m=u.call(g);if("[object Boolean]"==m)return""+g;if("[object Number]"==m)return g>-1/0&&g<1/0?""+g:"null";if("[object String]"==m)return R(""+g);if("object"==typeof g){for(a=d.length;a--;)if(d[a]===g)throw K();d.push(g);q=[];c=n;n+=f;if("[object Array]"==m){k=0;for(a=g.length;k<a;k++)m=O(k,g,b,h,f,n,d),q.push(m===w?"null":
m);a=q.length?f?"[\n"+n+q.join(",\n"+n)+"\n"+c+"]":"["+q.join(",")+"]":"[]"}else B(h||g,function(a){var c=O(a,g,b,h,f,n,d);c!==w&&q.push(R(a)+":"+(f?" ":"")+c)}),a=q.length?f?"{\n"+n+q.join(",\n"+n)+"\n"+c+"}":"{"+q.join(",")+"}":"{}";d.pop();return a}};r.stringify=function(a,c,b){var h,f,n,d;if(F[typeof c]&&c)if("[object Function]"==(d=u.call(c)))f=c;else if("[object Array]"==d){n={};for(var g=0,k=c.length,l;g<k;l=c[g++],(d=u.call(l),"[object String]"==d||"[object Number]"==d)&&(n[l]=1));}if(b)if("[object Number]"==
(d=u.call(b))){if(0<(b-=b%1))for(h="",10<b&&(b=10);h.length<b;h+=" ");}else"[object String]"==d&&(h=10>=b.length?b:b.slice(0,10));return O("",(l={},l[""]=a,l),f,n,h,"",[])}}if(!q("json-parse")){var V=A.fromCharCode,W={92:"\\",34:'"',47:"/",98:"\b",116:"\t",110:"\n",102:"\f",114:"\r"},b,J,l=function(){b=J=null;throw G();},z=function(){for(var a=J,c=a.length,e,h,f,k,d;b<c;)switch(d=a.charCodeAt(b),d){case 9:case 10:case 13:case 32:b++;break;case 123:case 125:case 91:case 93:case 58:case 44:return e=
D?a.charAt(b):a[b],b++,e;case 34:e="@";for(b++;b<c;)if(d=a.charCodeAt(b),32>d)l();else if(92==d)switch(d=a.charCodeAt(++b),d){case 92:case 34:case 47:case 98:case 116:case 110:case 102:case 114:e+=W[d];b++;break;case 117:h=++b;for(f=b+4;b<f;b++)d=a.charCodeAt(b),48<=d&&57>=d||97<=d&&102>=d||65<=d&&70>=d||l();e+=V("0x"+a.slice(h,b));break;default:l()}else{if(34==d)break;d=a.charCodeAt(b);for(h=b;32<=d&&92!=d&&34!=d;)d=a.charCodeAt(++b);e+=a.slice(h,b)}if(34==a.charCodeAt(b))return b++,e;l();default:h=
b;45==d&&(k=!0,d=a.charCodeAt(++b));if(48<=d&&57>=d){for(48==d&&(d=a.charCodeAt(b+1),48<=d&&57>=d)&&l();b<c&&(d=a.charCodeAt(b),48<=d&&57>=d);b++);if(46==a.charCodeAt(b)){for(f=++b;f<c&&(d=a.charCodeAt(f),48<=d&&57>=d);f++);f==b&&l();b=f}d=a.charCodeAt(b);if(101==d||69==d){d=a.charCodeAt(++b);43!=d&&45!=d||b++;for(f=b;f<c&&(d=a.charCodeAt(f),48<=d&&57>=d);f++);f==b&&l();b=f}return+a.slice(h,b)}k&&l();if("true"==a.slice(b,b+4))return b+=4,!0;if("false"==a.slice(b,b+5))return b+=5,!1;if("null"==a.slice(b,
b+4))return b+=4,null;l()}return"$"},P=function(a){var c,b;"$"==a&&l();if("string"==typeof a){if("@"==(D?a.charAt(0):a[0]))return a.slice(1);if("["==a){for(c=[];;b||(b=!0)){a=z();if("]"==a)break;b&&(","==a?(a=z(),"]"==a&&l()):l());","==a&&l();c.push(P(a))}return c}if("{"==a){for(c={};;b||(b=!0)){a=z();if("}"==a)break;b&&(","==a?(a=z(),"}"==a&&l()):l());","!=a&&"string"==typeof a&&"@"==(D?a.charAt(0):a[0])&&":"==z()||l();c[a.slice(1)]=P(z())}return c}l()}return a},T=function(a,b,e){e=S(a,b,e);e===
w?delete a[b]:a[b]=e},S=function(a,b,e){var h=a[b],f;if("object"==typeof h&&h)if("[object Array]"==u.call(h))for(f=h.length;f--;)T(h,f,e);else B(h,function(a){T(h,a,e)});return e.call(a,b,h)};r.parse=function(a,c){var e,h;b=0;J=""+a;e=P(z());"$"!=z()&&l();b=J=null;return c&&"[object Function]"==u.call(c)?S((h={},h[""]=e,h),"",c):e}}}r.runInContext=N;return r}var K=typeof define==="function"&&define.amd,F={"function":!0,object:!0},G=F[typeof exports]&&exports&&!exports.nodeType&&exports,k=F[typeof window]&&
window||this,t=G&&F[typeof module]&&module&&!module.nodeType&&"object"==typeof global&&global;!t||t.global!==t&&t.window!==t&&t.self!==t||(k=t);if(G&&!K)N(k,G);else{var L=k.JSON,Q=k.JSON3,M=!1,A=N(k,k.JSON3={noConflict:function(){M||(M=!0,k.JSON=L,k.JSON3=Q,L=Q=null);return A}});k.JSON={parse:A.parse,stringify:A.stringify}}K&&define(function(){return A})}).call(this);

var md5 = function (y){function x(c){return r(t(u(c),c.length*p))}function t(c,g){c[g>>5]|=128<<g%32;c[(g+64>>>9<<4)+14]=g;for(var b=1732584193,a=-271733879,d=-1732584194,e=271733878,f=0;f<c.length;f+=16)var p=b,q=a,r=d,t=e,b=h(b,a,d,e,c[f+0],7,-680876936),e=h(e,b,a,d,c[f+1],12,-389564586),d=h(d,e,b,a,c[f+2],17,606105819),a=h(a,d,e,b,c[f+3],22,-1044525330),b=h(b,a,d,e,c[f+4],7,-176418897),e=h(e,b,a,d,c[f+5],12,1200080426),d=h(d,e,b,a,c[f+6],17,-1473231341),a=h(a,d,e,b,c[f+7],22,-45705983),b=h(b,a,d,e,c[f+
8],7,1770035416),e=h(e,b,a,d,c[f+9],12,-1958414417),d=h(d,e,b,a,c[f+10],17,-42063),a=h(a,d,e,b,c[f+11],22,-1990404162),b=h(b,a,d,e,c[f+12],7,1804603682),e=h(e,b,a,d,c[f+13],12,-40341101),d=h(d,e,b,a,c[f+14],17,-1502002290),a=h(a,d,e,b,c[f+15],22,1236535329),b=k(b,a,d,e,c[f+1],5,-165796510),e=k(e,b,a,d,c[f+6],9,-1069501632),d=k(d,e,b,a,c[f+11],14,643717713),a=k(a,d,e,b,c[f+0],20,-373897302),b=k(b,a,d,e,c[f+5],5,-701558691),e=k(e,b,a,d,c[f+10],9,38016083),d=k(d,e,b,a,c[f+15],14,-660478335),a=k(a,d,
e,b,c[f+4],20,-405537848),b=k(b,a,d,e,c[f+9],5,568446438),e=k(e,b,a,d,c[f+14],9,-1019803690),d=k(d,e,b,a,c[f+3],14,-187363961),a=k(a,d,e,b,c[f+8],20,1163531501),b=k(b,a,d,e,c[f+13],5,-1444681467),e=k(e,b,a,d,c[f+2],9,-51403784),d=k(d,e,b,a,c[f+7],14,1735328473),a=k(a,d,e,b,c[f+12],20,-1926607734),b=l(b,a,d,e,c[f+5],4,-378558),e=l(e,b,a,d,c[f+8],11,-2022574463),d=l(d,e,b,a,c[f+11],16,1839030562),a=l(a,d,e,b,c[f+14],23,-35309556),b=l(b,a,d,e,c[f+1],4,-1530992060),e=l(e,b,a,d,c[f+4],11,1272893353),d=
l(d,e,b,a,c[f+7],16,-155497632),a=l(a,d,e,b,c[f+10],23,-1094730640),b=l(b,a,d,e,c[f+13],4,681279174),e=l(e,b,a,d,c[f+0],11,-358537222),d=l(d,e,b,a,c[f+3],16,-722521979),a=l(a,d,e,b,c[f+6],23,76029189),b=l(b,a,d,e,c[f+9],4,-640364487),e=l(e,b,a,d,c[f+12],11,-421815835),d=l(d,e,b,a,c[f+15],16,530742520),a=l(a,d,e,b,c[f+2],23,-995338651),b=m(b,a,d,e,c[f+0],6,-198630844),e=m(e,b,a,d,c[f+7],10,1126891415),d=m(d,e,b,a,c[f+14],15,-1416354905),a=m(a,d,e,b,c[f+5],21,-57434055),b=m(b,a,d,e,c[f+12],6,1700485571),
e=m(e,b,a,d,c[f+3],10,-1894986606),d=m(d,e,b,a,c[f+10],15,-1051523),a=m(a,d,e,b,c[f+1],21,-2054922799),b=m(b,a,d,e,c[f+8],6,1873313359),e=m(e,b,a,d,c[f+15],10,-30611744),d=m(d,e,b,a,c[f+6],15,-1560198380),a=m(a,d,e,b,c[f+13],21,1309151649),b=m(b,a,d,e,c[f+4],6,-145523070),e=m(e,b,a,d,c[f+11],10,-1120210379),d=m(d,e,b,a,c[f+2],15,718787259),a=m(a,d,e,b,c[f+9],21,-343485551),b=n(b,p),a=n(a,q),d=n(d,r),e=n(e,t);return[b,a,d,e]}function q(c,g,b,a,d,e){return n(v(n(n(g,c),n(a,e)),d),b)}function h(c,g,
b,a,d,e,f){return q(g&b|~g&a,c,g,d,e,f)}function k(c,g,b,a,d,e,f){return q(g&a|b&~a,c,g,d,e,f)}function l(c,g,b,a,d,e,f){return q(g^b^a,c,g,d,e,f)}function m(c,g,b,a,d,e,f){return q(b^(g|~a),c,g,d,e,f)}function n(c,g){var b=(c&65535)+(g&65535);return(c>>16)+(g>>16)+(b>>16)<<16|b&65535}function v(c,g){return c<<g|c>>>32-g}function u(c){for(var g=[],b=(1<<p)-1,a=0;a<c.length*p;a+=p)g[a>>5]|=(c.charCodeAt(a/p)&b)<<a%32;return g}function r(c){for(var g=w?"0123456789ABCDEF":"0123456789abcdef",b="",a=0;a<
4*c.length;a++)b+=g.charAt(c[a>>2]>>a%4*8+4&15)+g.charAt(c[a>>2]>>a%4*8&15);return b}function x(c){return r(t(u(c),c.length*p))}function t(c,g){c[g>>5]|=128<<g%32;c[(g+64>>>9<<4)+14]=g;for(var b=1732584193,a=-271733879,d=-1732584194,e=271733878,f=0;f<c.length;f+=16)var p=b,q=a,r=d,t=e,b=h(b,a,d,e,c[f+0],7,-680876936),e=h(e,b,a,d,c[f+1],12,-389564586),d=h(d,e,b,a,c[f+2],17,606105819),a=h(a,d,e,b,c[f+3],22,-1044525330),b=h(b,a,d,e,c[f+4],7,-176418897),e=h(e,b,a,d,c[f+5],12,1200080426),d=h(d,e,b,a,c[f+
6],17,-1473231341),a=h(a,d,e,b,c[f+7],22,-45705983),b=h(b,a,d,e,c[f+8],7,1770035416),e=h(e,b,a,d,c[f+9],12,-1958414417),d=h(d,e,b,a,c[f+10],17,-42063),a=h(a,d,e,b,c[f+11],22,-1990404162),b=h(b,a,d,e,c[f+12],7,1804603682),e=h(e,b,a,d,c[f+13],12,-40341101),d=h(d,e,b,a,c[f+14],17,-1502002290),a=h(a,d,e,b,c[f+15],22,1236535329),b=k(b,a,d,e,c[f+1],5,-165796510),e=k(e,b,a,d,c[f+6],9,-1069501632),d=k(d,e,b,a,c[f+11],14,643717713),a=k(a,d,e,b,c[f+0],20,-373897302),b=k(b,a,d,e,c[f+5],5,-701558691),e=k(e,b,
a,d,c[f+10],9,38016083),d=k(d,e,b,a,c[f+15],14,-660478335),a=k(a,d,e,b,c[f+4],20,-405537848),b=k(b,a,d,e,c[f+9],5,568446438),e=k(e,b,a,d,c[f+14],9,-1019803690),d=k(d,e,b,a,c[f+3],14,-187363961),a=k(a,d,e,b,c[f+8],20,1163531501),b=k(b,a,d,e,c[f+13],5,-1444681467),e=k(e,b,a,d,c[f+2],9,-51403784),d=k(d,e,b,a,c[f+7],14,1735328473),a=k(a,d,e,b,c[f+12],20,-1926607734),b=l(b,a,d,e,c[f+5],4,-378558),e=l(e,b,a,d,c[f+8],11,-2022574463),d=l(d,e,b,a,c[f+11],16,1839030562),a=l(a,d,e,b,c[f+14],23,-35309556),b=
l(b,a,d,e,c[f+1],4,-1530992060),e=l(e,b,a,d,c[f+4],11,1272893353),d=l(d,e,b,a,c[f+7],16,-155497632),a=l(a,d,e,b,c[f+10],23,-1094730640),b=l(b,a,d,e,c[f+13],4,681279174),e=l(e,b,a,d,c[f+0],11,-358537222),d=l(d,e,b,a,c[f+3],16,-722521979),a=l(a,d,e,b,c[f+6],23,76029189),b=l(b,a,d,e,c[f+9],4,-640364487),e=l(e,b,a,d,c[f+12],11,-421815835),d=l(d,e,b,a,c[f+15],16,530742520),a=l(a,d,e,b,c[f+2],23,-995338651),b=m(b,a,d,e,c[f+0],6,-198630844),e=m(e,b,a,d,c[f+7],10,1126891415),d=m(d,e,b,a,c[f+14],15,-1416354905),
a=m(a,d,e,b,c[f+5],21,-57434055),b=m(b,a,d,e,c[f+12],6,1700485571),e=m(e,b,a,d,c[f+3],10,-1894986606),d=m(d,e,b,a,c[f+10],15,-1051523),a=m(a,d,e,b,c[f+1],21,-2054922799),b=m(b,a,d,e,c[f+8],6,1873313359),e=m(e,b,a,d,c[f+15],10,-30611744),d=m(d,e,b,a,c[f+6],15,-1560198380),a=m(a,d,e,b,c[f+13],21,1309151649),b=m(b,a,d,e,c[f+4],6,-145523070),e=m(e,b,a,d,c[f+11],10,-1120210379),d=m(d,e,b,a,c[f+2],15,718787259),a=m(a,d,e,b,c[f+9],21,-343485551),b=n(b,p),a=n(a,q),d=n(d,r),e=n(e,t);return[b,a,d,e]}function q(c,
g,b,a,d,e){return n(v(n(n(g,c),n(a,e)),d),b)}function h(c,g,b,a,d,e,f){return q(g&b|~g&a,c,g,d,e,f)}function k(c,g,b,a,d,e,f){return q(g&a|b&~a,c,g,d,e,f)}function l(c,g,b,a,d,e,f){return q(g^b^a,c,g,d,e,f)}function m(c,g,b,a,d,e,f){return q(b^(g|~a),c,g,d,e,f)}function n(c,g){var b=(c&65535)+(g&65535);return(c>>16)+(g>>16)+(b>>16)<<16|b&65535}function v(c,g){return c<<g|c>>>32-g}function u(c){for(var g=[],b=(1<<p)-1,a=0;a<c.length*p;a+=p)g[a>>5]|=(c.charCodeAt(a/p)&b)<<a%32;return g}function r(c){for(var g=
w?"0123456789ABCDEF":"0123456789abcdef",b="",a=0;a<4*c.length;a++)b+=g.charAt(c[a>>2]>>a%4*8+4&15)+g.charAt(c[a>>2]>>a%4*8&15);return b}var w=0,p=8,w=0,p=8;return x(y)};


@if (!1)
    </script>
@endif
