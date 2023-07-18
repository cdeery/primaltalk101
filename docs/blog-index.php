<?php ini_set('default_charset','UTF-8');header('Content-Type: text/html; charset=UTF-8');header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');header('Cache-Control: post-check=0, pre-check=0', false);header('Pragma: no-cache'); ?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BLOG &mdash; Index</title>
<meta name="referrer" content="same-origin">
<meta name="robots" content="max-image-preview:large">
<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
<?php

    $pages = 10;
    $page = (isset($_GET['page']) ? $_GET['page'] : 1);
    if($page < 1) {
        $page = 1;
    }
    $current_page = 1;
    $current_result = 0;

    $blogName = 'blog-index';
    $blogJSON = file_get_contents($blogName . '.json');
    if($blogJSON === FALSE) {
        echo $blogName;
        exit(-1);
    }

    $blogData = json_decode($blogJSON, TRUE);
    if($blogData == NULL) {
        echo "JSON";
        exit(-2);
    }

    $blogPostsPerPage = $blogData['blogPostsPerPage'];
    $blogPostsMargin = $blogData['blogPostsMargin'];
    $blogPosts = $blogData['blogPosts'];
    $tag = (isset($_GET['tag']) ? $_GET['tag'] : NULL);
    if($tag !== NULL) {
        $filteredBlogPosts = array();
        foreach($blogPosts as $blogPost) {
            if(in_array($tag, $blogPost['tags'])) {
                $filteredBlogPosts[] = $blogPost;
            }
        }
        $blogPosts = $filteredBlogPosts;
    }
    $devices = $blogData['devices'];
    $css = $blogData['css'];
    $mq = $blogData['mq'];

    $end_page = $page + $pages / 2 - 1;
    if($end_page < $pages) {
        $end_page = $pages;
    }
    $blogPostsCount = count($blogPosts);
    $blogPostsPages = intval(($blogPostsCount - 1) / $blogPostsPerPage) + 1;
    if($blogPostsPages < $end_page) {
        $end_page = $blogPostsPages;
    }

    $start_page = $end_page + 1 - $pages;
    if($start_page < 1) {
        $start_page = 1;
    }

    $style = '';
    foreach($devices as $deviceInfo) {
        $pos = strpos($deviceInfo, ':');
        $device = substr($deviceInfo, 0, $pos);
        $deviceWidth = substr($deviceInfo, $pos + 1);
        if(!isset($css[$device])) continue;
        $deviceCSSClasses = $css[$device];
        $mediaQuery = (isset($mq[$device]) ? $mq[$device] : NULL);
        if($mediaQuery !== NULL) {
            $style .= "@media " . $mediaQuery . ' {';
        }
        $style .= ".bpwc{width:100%;margin:auto}";
        $style .= ".bpc{width:" . $deviceWidth . "px;margin:auto}";
        $style .= ".bpm{margin-top:" . $blogPostsMargin[$device] . "px}";
        $cssClassesAdded = array();
        $blogPostIndex = ($page - 1) * $blogPostsPerPage;
        $count = 0;
        while($blogPostIndex < $blogPostsCount && ++$count <= $blogPostsPerPage) {
            $blogPost = $blogPosts[$blogPostIndex++];

            $cssClasses = $blogPost['cssClasses'];
            foreach($cssClasses as $cssClass) {
                if(!in_array($cssClass, $cssClassesAdded) && isset($deviceCSSClasses[$cssClass])) {
                    $style .= $deviceCSSClasses[$cssClass];
                }
                $cssClassesAdded[] = $cssClass;
            }
        }
        if($mediaQuery !== NULL) {
            $style .= '}';
        }
    }
    echo "<style>" . $style . "</style>";

?>

<link rel="preload" href="css/Lato-Regular.woff2" as="font" crossorigin>
<style>html,body{-webkit-text-zoom:reset !important}@font-face{font-display:block;font-family:"Lato";src:url('css/Lato-Regular.woff2') format('woff2'),url('css/Lato-Regular.woff') format('woff');font-weight:400}@font-face{font-display:block;font-family:"Lato";src:url('css/Lato-Black.woff2') format('woff2'),url('css/Lato-Black.woff') format('woff');font-weight:900}@font-face{font-display:block;font-family:"Lato";src:url('css/Lato-Light.woff2') format('woff2'),url('css/Lato-Light.woff') format('woff');font-weight:300}body>div{font-size:0}p,span,h1,h2,h3,h4,h5,h6,a,li{margin:0;word-spacing:normal;word-wrap:break-word;-ms-word-wrap:break-word;pointer-events:auto;-ms-text-size-adjust:none !important;-moz-text-size-adjust:none !important;-webkit-text-size-adjust:none !important;text-size-adjust:none !important;max-height:10000000px}sup{font-size:inherit;vertical-align:baseline;position:relative;top:-0.4em}sub{font-size:inherit;vertical-align:baseline;position:relative;top:0.4em}ul{display:block;word-spacing:normal;word-wrap:break-word;list-style-type:none;padding:0;margin:0;-moz-padding-start:0;-khtml-padding-start:0;-webkit-padding-start:0;-o-padding-start:0;-padding-start:0;-webkit-margin-before:0;-webkit-margin-after:0}li{display:block;white-space:normal}li p{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none}form{display:inline-block}a{text-decoration:inherit;color:inherit;-webkit-tap-highlight-color:rgba(0,0,0,0)}textarea{resize:none}.shm-l{float:left;clear:left}.shm-r{float:right;clear:right}.btf{display:none}.plyr{min-width:0 !important}html{font-family:sans-serif}body{font-size:0;margin:0;--z:1;zoom:var(--z)}audio,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background:0 0;outline:0}b,strong{font-weight:700}dfn{font-style:italic}h1,h2,h3,h4,h5,h6{font-size:1em;line-height:1;margin:0}img{border:0}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=submit]{-webkit-appearance:button;cursor:pointer;box-sizing:border-box;white-space:normal}input[type=date],input[type=email],input[type=number],input[type=password],input[type=text],textarea{-webkit-appearance:none;appearance:none;box-sizing:border-box}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type=checkbox],input[type=radio]{box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}textarea{overflow:auto;box-sizing:border-box;border-color:#ddd}optgroup{font-weight:700}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}blockquote{margin-block-start:0;margin-block-end:0;margin-inline-start:0;margin-inline-end:0}:-webkit-full-screen-ancestor:not(iframe){-webkit-clip-path:initial !important}
html{-webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale}.menu-content{cursor:pointer;position:relative}li{-webkit-tap-highlight-color:rgba(0,0,0,0)}
#b{background-color:#fff}.ps283{position:relative;margin-top:15px}.v72{display:block;vertical-align:top}.s310{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:58px}.v73{display:inline-block;vertical-align:top}.ps284{position:relative;margin-left:0;margin-top:0}.s311{min-width:310px;width:310px;min-height:58px}.s312{min-width:310px;width:310px;min-height:58px}.c255{z-index:2;pointer-events:auto;overflow:hidden;height:58px}.p32{text-indent:0;padding-bottom:0;padding-right:0;text-align:left}.f93{font-family:"Helvetica Neue", sans-serif;font-size:47px;font-size:calc(47px * var(--f));line-height:1.107;font-weight:300;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#000;background-color:initial;text-shadow:none}.v74{display:none;vertical-align:top;overflow:visible}.v75{display:none;vertical-align:top}.s314{min-width:237px;width:237px;min-height:84px;height:84px}.m11{padding:0px 0px 0px 0px}.mcv11{display:inline-block}.s315{min-width:237px;width:237px;min-height:84px}.c257{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-image:url(images/hamburger-gray-84-1.png);background-color:transparent;background-repeat:no-repeat;background-position:50% 50%;background-size:contain}.webp .c257{background-image:url(images/hamburger-gray-84-1.webp)}.ps286{position:relative;margin-left:0;margin-top:32px}.s316{min-width:237px;width:237px;min-height:19px}.c258{pointer-events:auto;overflow:hidden;height:19px}.p33{text-indent:0;padding-bottom:0;padding-right:0;text-align:center}.f94{font-family:Lato;font-size:12px;font-size:calc(12px * var(--f));line-height:1.334;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:transparent;background-color:initial;text-shadow:none}.v76{display:none;vertical-align:top}.s317{min-width:182px;width:182px;min-height:351px;height:351px}.ml11{outline:0}.s318{min-width:182px;width:182px;min-height:174px;height:174px}.s319{min-width:182px;width:182px;min-height:174px}.c259{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps287{position:relative;margin-left:0;margin-top:43px}.s320{min-width:182px;width:182px;min-height:88px}.c260{pointer-events:auto;overflow:hidden;height:88px}.f95{font-family:Lato;font-size:60px;font-size:calc(60px * var(--f));line-height:1.318;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps288{position:relative;margin-left:0;margin-top:3px}.c261{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps289{position:relative;margin-left:0;margin-top:43px}.ps290{position:relative;margin-left:0;margin-top:96px}.c262{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps291{position:relative;margin-left:0;margin-top:71px}.s321{min-width:182px;width:182px;min-height:31px}.c263{pointer-events:auto;overflow:hidden;height:31px}.f96{font-family:Lato;font-size:20px;font-size:calc(20px * var(--f));line-height:1.351;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps292{position:relative;margin-left:0;margin-top:96px}.c264{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps293{position:relative;margin-left:0;margin-top:71px}.c265{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.c266{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v77{display:block;vertical-align:top}.ps294{position:relative;margin-top:0}.s322{width:100%;min-width:960px;min-height:76px}.c267{z-index:3;pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps295{display:inline-block;width:0;height:0}.ps296{position:relative;margin-top:20px}.s323{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:56px}.ps297{position:relative;margin-left:6px;margin-top:0}.s324{min-width:929px;width:929px;min-height:56px}.ps298{position:relative;margin-left:0;margin-top:4px}.s325{min-width:83px;width:83px;min-height:49px}.c268{z-index:4;pointer-events:auto}.f97{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:10px;padding-bottom:9px;margin-top:0;margin-bottom:0}.btn61{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn61:hover{background-color:#82939e;border-color:#000;color:#000}.btn61:active{background-color:#52646f;border-color:#000;color:#fff}.v78{display:inline-block;overflow:hidden;outline:0}.s326{width:83px;padding-right:0;height:30px}.ps299{position:relative;margin-left:43px;margin-top:4px}.c269{z-index:5;pointer-events:auto}.btn62{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn62:hover{background-color:#82939e;border-color:#000;color:#000}.btn62:active{background-color:#52646f;border-color:#000;color:#fff}.ps300{position:relative;margin-left:43px;margin-top:4px}.c270{z-index:6;pointer-events:auto}.btn63{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn63:hover{background-color:#82939e;border-color:#000;color:#000}.btn63:active{background-color:#52646f;border-color:#000;color:#fff}.ps301{position:relative;margin-left:44px;margin-top:4px}.s327{min-width:114px;width:114px;min-height:49px}.c271{z-index:7;pointer-events:auto}.btn64{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn64:hover{background-color:#82939e;border-color:#000;color:#000}.btn64:active{background-color:#52646f;border-color:#000;color:#fff}.s328{width:114px;padding-right:0;height:30px}.ps302{position:relative;margin-left:43px;margin-top:0}.s329{min-width:170px;width:170px;min-height:56px}.c272{z-index:8;pointer-events:auto}.f98{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn65{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn65:hover{background-color:#82939e;border-color:#000;color:#000}.btn65:active{background-color:#52646f;border-color:#000;color:#fff}.s330{width:170px;padding-right:0;height:30px}.ps303{position:relative;margin-left:43px;margin-top:0}.s331{min-width:180px;width:180px;min-height:56px}.c273{z-index:9;pointer-events:auto}.btn66{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn66:hover{background-color:#82939e;border-color:#000;color:#000}.btn66:active{background-color:#52646f;border-color:#000;color:#fff}.s332{width:180px;padding-right:0;height:30px}.v79{display:none;vertical-align:top}.v80{display:inline-block;vertical-align:top;overflow:hidden}.ps306{position:relative;margin-top:109px}.s335{width:100%;min-width:960px}.c275{z-index:10;pointer-events:none}.ps307{position:relative;margin-top:0}.s336{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:2125px}.s337{width:100%;min-width:960px;min-height:1427px}.c276{z-index:11}.ps308{position:relative;margin-left:120px;margin-top:0}.s338{min-width:720px;width:720px;min-height:94px}.c277{z-index:12;pointer-events:auto;overflow:hidden;height:94px}.f99{font-family:Lato;font-size:36px;font-size:calc(36px * var(--f));line-height:1.307;font-weight:300;font-style:normal;text-decoration:underline;text-transform:none;letter-spacing:normal;color:#54387d;background-color:initial;text-shadow:none}.ps309{position:relative;margin-left:143px;margin-top:51px}.s339{min-width:730px;width:730px;min-height:410px;height:410px}.c278{z-index:13;pointer-events:auto}.i20{position:absolute;left:0;width:730px;height:410px;top:0;border:0}.i21{width:100%;height:100%;display:inline-block;-webkit-transform:translate3d(0,0,0)}.ps310{position:relative;margin-left:120px;margin-top:33px}.s340{min-width:753px;width:753px;min-height:839px}.c279{z-index:14;pointer-events:auto;overflow:hidden;height:839px}.f100{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.f101{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.ps311{position:relative;margin-top:100px}.s341{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:40px}.ps312{display:inline-block;position:relative;left:50%;-ms-transform:translate(-50%,0);-webkit-transform:translate(-50%,0);transform:translate(-50%,0)}.ps313{position:relative;margin-left:126px;margin-top:0}.s342{min-width:708px;width:708px;min-height:40px}.c280{z-index:19}.s343{min-width:48px;width:48px;min-height:40px}.c281{z-index:20;pointer-events:auto}.f102{font-family:"Helvetica Neue", sans-serif;font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn67{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn67:hover{background-color:#82939e;border-color:#000;color:#000}.btn67:active{background-color:#52646f;border-color:#000;color:#fff}.s344{width:48px;padding-right:0;height:14px}.ps314{position:relative;margin-left:12px;margin-top:0}.c282{z-index:21;pointer-events:auto}.btn68{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn68:hover{background-color:#82939e;border-color:#000;color:#000}.btn68:active{background-color:#52646f;border-color:#000;color:#fff}.c283{z-index:22;pointer-events:auto}.btn69{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn69:hover{background-color:#82939e;border-color:#000;color:#000}.btn69:active{background-color:#52646f;border-color:#000;color:#fff}.c284{z-index:23;pointer-events:auto}.btn70{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn70:hover{background-color:#82939e;border-color:#000;color:#000}.btn70:active{background-color:#52646f;border-color:#000;color:#fff}.c285{z-index:24;pointer-events:auto}.btn71{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn71:hover{background-color:#82939e;border-color:#000;color:#000}.btn71:active{background-color:#52646f;border-color:#000;color:#fff}.c286{z-index:25;pointer-events:auto}.btn72{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn72:hover{background-color:#82939e;border-color:#000;color:#000}.btn72:active{background-color:#52646f;border-color:#000;color:#fff}.c287{z-index:26;pointer-events:auto}.btn73{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn73:hover{background-color:#82939e;border-color:#000;color:#000}.btn73:active{background-color:#52646f;border-color:#000;color:#fff}.c288{z-index:27;pointer-events:auto}.btn74{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn74:hover{background-color:#82939e;border-color:#000;color:#000}.btn74:active{background-color:#52646f;border-color:#000;color:#fff}.c289{z-index:28;pointer-events:auto}.btn75{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn75:hover{background-color:#82939e;border-color:#000;color:#000}.btn75:active{background-color:#52646f;border-color:#000;color:#fff}.c290{z-index:29;pointer-events:auto}.btn76{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn76:hover{background-color:#82939e;border-color:#000;color:#000}.btn76:active{background-color:#52646f;border-color:#000;color:#fff}.c291{z-index:30;pointer-events:auto}.btn77{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn77:hover{background-color:#82939e;border-color:#000;color:#000}.btn77:active{background-color:#52646f;border-color:#000;color:#fff}.c292{z-index:31;pointer-events:auto}.btn78{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn78:hover{background-color:#82939e;border-color:#000;color:#000}.btn78:active{background-color:#52646f;border-color:#000;color:#fff}body{--d:0;--s:960}@media (min-width:768px) and (max-width:959px) {.ps283{margin-top:12px}.s310{min-width:768px;width:768px;min-height:46px}.s311{min-width:248px;width:248px;min-height:46px}.s312{min-width:248px;width:248px;min-height:46px}.c255{height:46px}.f93{font-size:37px;font-size:calc(37px * var(--f));line-height:1.109}.s314{min-width:190px;width:190px;min-height:67px;height:67px}.s315{min-width:190px;width:190px;min-height:67px}.c257{background-image:url(images/hamburger-gray-67.png)}.webp .c257{background-image:url(images/hamburger-gray-67.webp)}.ps286{margin-top:26px}.s316{min-width:190px;width:190px;min-height:15px}.c258{height:15px}.f94{font-size:9px;font-size:calc(9px * var(--f))}.s317{min-width:146px;width:146px;min-height:280px;height:280px}.s318{min-width:146px;width:146px;min-height:139px;height:139px}.s319{min-width:146px;width:146px;min-height:139px}.ps287{margin-top:34px}.s320{min-width:146px;width:146px;min-height:71px}.c260{height:71px}.f95{font-size:48px;font-size:calc(48px * var(--f));line-height:1.313}.ps288{margin-top:2px}.ps289{margin-top:34px}.ps290{margin-top:76px}.ps291{margin-top:54px}.s321{min-width:146px;width:146px}.ps292{margin-top:76px}.ps293{margin-top:54px}.s322{min-width:768px;min-height:61px}.ps296{margin-top:16px}.s323{min-width:768px;width:768px;min-height:45px}.ps297{margin-left:5px}.s324{min-width:743px;width:743px;min-height:45px}.s325{min-width:66px;width:66px;min-height:39px}.f97{font-size:20px;font-size:calc(20px * var(--f));padding-top:8px;padding-bottom:7px}.s326{width:66px;height:24px}.ps299{margin-left:35px}.ps300{margin-left:34px}.ps301{margin-left:36px}.s327{min-width:91px;width:91px;min-height:39px}.s328{width:91px;height:24px}.ps302{margin-left:35px}.s329{min-width:136px;width:136px;min-height:45px}.f98{font-size:20px;font-size:calc(20px * var(--f));padding-top:11px;padding-bottom:10px}.s330{width:136px;height:24px}.ps303{margin-left:34px}.s331{min-width:144px;width:144px;min-height:45px}.s332{width:144px;height:24px}.ps306{margin-top:87px}.s335{min-width:768px}.s336{min-width:768px;width:768px;min-height:1701px}.s337{min-width:768px;min-height:1142px}.ps308{margin-left:96px}.s338{min-width:576px;width:576px;min-height:75px}.c277{height:75px}.f99{font-size:28px;font-size:calc(28px * var(--f));line-height:1.322}.ps309{margin-left:114px;margin-top:41px}.s339{min-width:584px;width:584px;min-height:328px;height:328px}.i20{width:584px;height:328px}.ps310{margin-left:96px;margin-top:27px}.s340{min-width:602px;width:602px;min-height:671px}.c279{height:671px}.f100{font-size:12px;font-size:calc(12px * var(--f));line-height:1.751}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:12px;font-size:calc(12px * var(--f))}.ps311{margin-top:80px}.s341{min-width:768px;width:768px;min-height:32px}.ps313{margin-left:101px}.s342{min-width:566px;width:566px;min-height:32px}.s343{min-width:38px;width:38px;min-height:32px}.f102{font-size:9px;font-size:calc(9px * var(--f));line-height:1.223;padding-top:11px;padding-bottom:10px}.s344{width:38px;height:11px}.ps314{margin-left:10px}body{--d:1;--s:768}}@media (min-width:480px) and (max-width:767px) {.ps283{margin-top:8px}.s310{min-width:480px;width:480px;min-height:105px}.s311{min-width:270px;width:270px;min-height:105px}.s312{min-width:270px;width:270px;min-height:56px}.c255{height:56px}.f93{font-size:40px;font-size:calc(40px * var(--f));line-height:1.126}.v74{display:inline-block}.ps285{position:relative;margin-left:5px;margin-top:7px}.s313{min-width:119px;width:119px;min-height:42px;height:42px}.c256{z-index:32;pointer-events:auto}.v75{display:inline-block}.s314{min-width:119px;width:119px;min-height:42px;height:42px}.s315{min-width:119px;width:119px;min-height:42px}.c257{background-image:url(images/hamburger-gray-42.png)}.webp .c257{background-image:url(images/hamburger-gray-42.webp)}.ps286{margin-top:16px}.s316{min-width:119px;width:119px;min-height:10px}.c258{height:10px}.f94{font-size:6px;font-size:calc(6px * var(--f))}.s317{min-width:401px;width:401px;min-height:530px;height:530px}.s318{min-width:401px;width:401px;min-height:87px;height:87px}.s319{min-width:401px;width:401px;min-height:87px}.ps287{margin-top:21px}.s320{min-width:401px;width:401px;min-height:46px}.c260{height:46px}.f95{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps288{margin-top:2px}.ps289{margin-top:20px}.ps290{margin-top:1px}.ps291{margin-top:21px}.s321{min-width:401px;width:401px;min-height:46px}.c263{height:46px}.f96{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps292{margin-top:2px}.ps293{margin-top:20px}.v77{display:none}.ps296{margin-top:0}.s323{min-width:480px;width:480px;min-height:29px}.ps297{margin-left:3px}.s324{min-width:465px;width:465px;min-height:29px}.ps298{margin-top:2px}.s325{min-width:42px;width:42px;min-height:24px}.f97{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:5px;padding-bottom:5px}.s326{width:42px;height:14px}.ps299{margin-left:11px;margin-top:2px}.ps300{margin-left:10px;margin-top:2px}.ps301{margin-left:63px;margin-top:2px}.s327{min-width:57px;width:57px;min-height:24px}.s328{width:57px;height:14px}.ps302{margin-left:12px}.s329{min-width:86px;width:86px;min-height:29px}.f98{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:8px;padding-bottom:7px}.s330{width:86px;height:14px}.ps303{margin-left:10px}.s331{min-width:90px;width:90px;min-height:29px}.s332{width:90px;height:14px}.ps304{position:relative;margin-top:-50px}.v79{display:block}.s333{pointer-events:none;min-width:480px;width:480px;margin-left:auto;margin-right:auto;min-height:56px}.ps305{position:relative;margin-left:0;margin-top:0}.s334{min-width:480px;width:480px;min-height:56px}.c274{z-index:1;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps306{margin-top:115px}.s335{min-width:480px}.s336{min-width:480px;width:480px;min-height:5017px}.s337{min-width:480px;min-height:3048px}.ps308{margin-left:26px}.s338{min-width:428px;width:428px;min-height:104px}.c277{height:104px}.f99{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps309{margin-left:26px;margin-top:31px}.s339{min-width:428px;width:428px;min-height:261px;height:261px}.i20{width:428px;height:240px;top:11px}.ps310{margin-left:26px}.s340{min-width:438px;width:438px;min-height:2619px}.c279{height:2619px}.f100{font-size:24px;font-size:calc(24px * var(--f))}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:24px;font-size:calc(24px * var(--f));line-height:1.626}.ps311{margin-top:-4132px}.s341{min-width:480px;width:480px;min-height:20px}.ps313{margin-left:63px}.s342{min-width:354px;width:354px;min-height:20px}.s343{min-width:24px;width:24px;min-height:20px}.f102{font-size:6px;font-size:calc(6px * var(--f));padding-top:7px;padding-bottom:6px}.s344{width:24px;height:7px}.ps314{margin-left:6px}body{--d:2;--s:480}}@media (max-width:479px) {.ps283{margin-top:5px}.s310{min-width:320px;width:320px;min-height:70px}.s311{min-width:180px;width:180px;min-height:70px}.s312{min-width:180px;width:180px;min-height:37px}.c255{height:37px}.f93{font-size:27px;font-size:calc(27px * var(--f));line-height:1.112}.v74{display:inline-block}.ps285{position:relative;margin-left:3px;margin-top:5px}.s313{min-width:79px;width:79px;min-height:28px;height:28px}.c256{z-index:32;pointer-events:auto}.v75{display:inline-block}.s314{min-width:79px;width:79px;min-height:28px;height:28px}.s315{min-width:79px;width:79px;min-height:28px}.c257{background-image:url(images/hamburger-gray-28.png)}.webp .c257{background-image:url(images/hamburger-gray-28.webp)}.ps286{margin-top:10px}.s316{min-width:79px;width:79px;min-height:7px}.c258{height:7px}.f94{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251}.s317{min-width:267px;width:267px;min-height:353px;height:353px}.s318{min-width:267px;width:267px;min-height:58px;height:58px}.s319{min-width:267px;width:267px;min-height:58px}.ps287{margin-top:14px}.s320{min-width:267px;width:267px;min-height:31px}.c260{height:31px}.f95{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps288{margin-top:1px}.ps289{margin-top:14px}.ps290{margin-top:1px}.ps291{margin-top:14px}.s321{min-width:267px;width:267px}.ps292{margin-top:1px}.ps293{margin-top:14px}.v77{display:none}.ps296{margin-top:0}.s323{min-width:320px;width:320px;min-height:19px}.ps297{margin-left:2px}.s324{min-width:310px;width:310px;min-height:19px}.ps298{margin-top:1px}.s325{min-width:28px;width:28px;min-height:16px}.f97{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:3px;padding-bottom:3px}.s326{width:28px;height:10px}.ps299{margin-left:7px;margin-top:1px}.ps300{margin-left:7px;margin-top:1px}.ps301{margin-left:42px;margin-top:1px}.s327{min-width:38px;width:38px;min-height:16px}.s328{width:38px;height:10px}.ps302{margin-left:8px}.s329{min-width:57px;width:57px;min-height:19px}.f98{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:5px;padding-bottom:4px}.s330{width:57px;height:10px}.ps303{margin-left:7px}.s331{min-width:60px;width:60px;min-height:19px}.s332{width:60px;height:10px}.ps304{position:relative;margin-top:-33px}.v79{display:block}.s333{pointer-events:none;min-width:320px;width:320px;margin-left:auto;margin-right:auto;min-height:37px}.ps305{position:relative;margin-left:0;margin-top:0}.s334{min-width:320px;width:320px;min-height:37px}.c274{z-index:1;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps306{margin-top:77px}.s335{min-width:320px}.s336{min-width:320px;width:320px;min-height:3345px}.s337{min-width:320px;min-height:2032px}.ps308{margin-left:17px}.s338{min-width:285px;width:285px;min-height:69px}.c277{height:69px}.f99{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps309{margin-left:17px;margin-top:21px}.s339{min-width:285px;width:285px;min-height:174px;height:174px}.i20{width:285px;height:160px;top:7px}.ps310{margin-left:17px;margin-top:22px}.s340{min-width:292px;width:292px;min-height:1746px}.c279{height:1746px}.f100{font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.ps311{margin-top:-2754px}.s341{min-width:320px;width:320px;min-height:13px}.ps313{margin-left:42px}.s342{min-width:236px;width:236px;min-height:13px}.s343{min-width:16px;width:16px;min-height:13px}.f102{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251;padding-top:4px;padding-bottom:4px}.s344{width:16px;height:5px}.ps314{margin-left:4px}body{--d:3;--s:320}}@media (-webkit-min-device-pixel-ratio:1.7), (min-resolution:144dpi) {.c257{background-image:url(images/hamburger-gray-168.png);background-size:contain}.webp .c257{background-image:url(images/hamburger-gray-168.webp)}}@media (min-width:768px) and (max-width:959px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:768px) and (max-width:959px) and (min-resolution:144dpi) {.c257{background-image:url(images/hamburger-gray-134.png);background-size:contain}.webp .c257{background-image:url(images/hamburger-gray-134.webp)}}@media (min-width:480px) and (max-width:767px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:480px) and (max-width:767px) and (min-resolution:144dpi) {.c257{background-image:url(images/hamburger-gray-84.png);background-size:contain}.webp .c257{background-image:url(images/hamburger-gray-84.webp)}}@media (max-width:479px) and (-webkit-min-device-pixel-ratio:1.7), (max-width:479px) and (min-resolution:144dpi) {.c257{background-image:url(images/hamburger-gray-56.png);background-size:contain}.webp .c257{background-image:url(images/hamburger-gray-56.webp)}}</style>
<script>!function(){var A=new Image;A.onload=A.onerror=function(){1!=A.height&&document.body.classList.remove("webp")},A.src="data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAD8D+JaQAA3AA/ua1AAA"}();
</script>
<link onload="this.media='all';this.onload=null;" rel="stylesheet" href="css/site.c177e0.css" media="print">
</head>
<body class="webp" id="b">
<script>var p=document.createElement("P");p.innerHTML="&nbsp;",p.style.cssText="position:fixed;visible:hidden;font-size:100px;zoom:1",document.body.appendChild(p);var rsz=function(e){return function(){var r=Math.trunc(1e3/parseFloat(window.getComputedStyle(e).getPropertyValue("font-size")))/10,t=document.body;r!=t.style.getPropertyValue("--f")&&t.style.setProperty("--f",r)}}(p);if("ResizeObserver"in window){var ro=new ResizeObserver(rsz);ro.observe(p)}else if("requestAnimationFrame"in window){var raf=function(){rsz(),requestAnimationFrame(raf)};requestAnimationFrame(raf)}else setInterval(rsz,100);</script>

<script>!function(){var e=function(){var e=document.body,n=window.innerWidth,t=getComputedStyle(e).getPropertyValue("--s");n=320==t?Math.min(479,n):480==t?Math.min(610,n):t,e.style.setProperty("--z",Math.trunc(n/t*1000)/1000)};window.addEventListener?window.addEventListener("resize",e,!0):window.onscroll=e,e()}();</script>

<div class="ps283 v72 s310">
<div class="v73 ps284 s311 c254">
<div class="v73 ps284 s312 c255">
<p class="p32 f93"><a href="#">PrimalTalk101</a></p>
</div>
<div class="v74 ps285 s313 c256">
<ul class="menu-dropdown v75 ps284 s314 m11" id="m1">
<li class="v73 ps284 s314 mit11">
<div class="menu-content mcv11">
<div class="v73 ps284 s315 c257">
<div class="v73 ps286 s316 c258">
<p class="p33 f94">Menu</p>
</div>
</div>
</div>
<ul class="menu-dropdown-1 v76 ps284 s317 m11">
<li class="v73 ps284 s318 mit11">
<a href="./" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c259"><div class="v73 ps287 s320 c260"><p class="p32 f95">Home</p></div></div></div></a>
</li>
<li class="v73 ps288 s318 mit11">
<a href="about.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c261"><div class="v73 ps289 s320 c260"><p class="p32 f95">About</p></div></div></div></a>
</li>
<li class="v75 ps290 s318 mit11">
<a href="media.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c262"><div class="v73 ps291 s321 c263"><p class="p32 f96">Media</p></div></div></div></a>
</li>
<li class="v75 ps292 s318 mit11">
<a href="contact.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c264"><div class="v73 ps293 s321 c263"><p class="p32 f96">Contact</p></div></div></div></a>
</li>
<li class="v75 ps290 s318 mit11">
<a href="special-offer.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c265"><div class="v73 ps291 s321 c263"><p class="p32 f96">Special Offer</p></div></div></div></a>
</li>
<li class="v75 ps292 s318 mit11">
<a href="work-with-me.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps284 s319 c266"><div class="v73 ps293 s321 c263"><p class="p32 f96">Work with me!</p></div></div></div></a>
</li>
</ul>
</li>
</ul>
</div>
</div>
</div>
<div class="v77 ps294 s322 c267">
<div class="ps295">
</div>
<div class="ps296 v72 s323">
<div class="v73 ps297 s324 c254">
<div class="v73 ps298 s325 c268">
<a href="./" class="f97 btn61 v78 s326">Home</a>
</div>
<div class="v73 ps299 s325 c269">
<a href="about.html" class="f97 btn62 v78 s326">About</a>
</div>
<div class="v73 ps300 s325 c270">
<a href="media.html" class="f97 btn63 v78 s326">Media</a>
</div>
<div class="v73 ps301 s327 c271">
<a href="contact.html" class="f97 btn64 v78 s328">Contact</a>
</div>
<div class="v73 ps302 s329 c272">
<a href="special-offer.html" class="f98 btn65 v78 s330">Special Offer</a>
</div>
<div class="v73 ps303 s331 c273">
<a href="work-with-me.html" class="f98 btn66 v78 s332">Work with me!</a>
</div>
</div>
</div>
</div>
<div class="ps304 v79 s333">
<div class="v75 ps305 s334 c274"></div>
</div>
<div class="v80 ps306 s335 c275">
<?php

    $blogPostIndex = ($page - 1) * $blogPostsPerPage;
    $documentReady = '';
    $documentLoad = '';
    $facebookFix = '';
    $resizeImages = '';
    $animations = '';
    $count = 0;
    while($blogPostIndex < $blogPostsCount && ++$count <= $blogPostsPerPage) {
        $blogPost = $blogPosts[$blogPostIndex++];

        echo '<article class="bp';
        if($blogPost['w']) echo 'w';
        echo 'c';
        if($count > 1) echo ' bpm';
        echo '">';
        echo $blogPost['html'];
        echo '</article>';

        $documentReady .= $blogPost['documentReady'];
        $documentLoad .= $blogPost['documentLoad'];
        $facebookFix .= $blogPost['facebookFix'];
        $resizeImages .= $blogPost['resizeImages'];
        $animations .= $blogPost['animations'];
    }

    echo '<script>var blogDocumentReady=function(){' . $documentReady . '}';
    echo ',blogDocumentLoad=function(){' . $documentLoad . '}';
    echo ',blogFacebookFix=function(){' . $facebookFix . '}';
    echo ',blogResizeImages=function(){' . $resizeImages . '}';
    echo ',blogAnimationsSetup=function(){' . $animations . '}';
    echo '</script>';

?>

</div>
<div class="ps311 v72 s341">
<div class="v73 ps313 s342 c280">
<div class="ps312">
<?php

    echo '<style>.pbdn{display:none}.pbc{border: 0;background-color:#c0c0c0;color:#fff;border-color:#677a85}</style>';
    $control = '<div class="v73 ps284 s343 c281 {btnclass}"><a href="#" class="f102 btn67 v78 s344 {lnkclass}">&lt;&lt;</a></div>';
    if($page > 1) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . ($page - 1);
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        $control = str_replace('href="#"', 'href="' . $url . '"', $control);
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c282 {btnclass}"><a href="#" class="f102 btn68 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 1 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c283 {btnclass}"><a href="#" class="f102 btn69 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 2 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c284 {btnclass}"><a href="#" class="f102 btn70 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 3 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c285 {btnclass}"><a href="#" class="f102 btn71 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 4 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c286 {btnclass}"><a href="#" class="f102 btn72 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 5 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c287 {btnclass}"><a href="#" class="f102 btn73 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 6 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c288 {btnclass}"><a href="#" class="f102 btn74 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 7 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c289 {btnclass}"><a href="#" class="f102 btn75 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 8 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c290 {btnclass}"><a href="#" class="f102 btn76 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 9 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c291 {btnclass}"><a href="#" class="f102 btn77 v78 s344 {lnkclass}">{page_num}</a></div>';
    $buttonPage = $start_page + 10 - 1;
    if($buttonPage <= $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . $buttonPage;
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        if($buttonPage == $page) {
            $control = str_replace('href="#"', '', $control);
            $control = str_replace('{lnkclass}', 'pbc', $control);
        }
        else {
            $control = str_replace('href="#"', 'href="' . $url . '"', $control);
            $control = str_replace('{lnkclass}', '', $control);
        }
        $control = str_replace('{page_num}', $buttonPage, $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{page_num}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

<?php

    $control = '<div class="v73 ps314 s343 c292 {btnclass}"><a href="#" class="f102 btn78 v78 s344 {lnkclass}">&gt;&gt;</a></div>';
    if($page < $end_page) {
        $url = strtok($_SERVER['REQUEST_URI'],'?') . '?page=' . ($page + 1);
        if($tag !== NULL) {
            $url .= '&tag=' . $tag;
        }
        $control = str_replace('href="#"', 'href="' . $url . '"', $control);
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{btnclass}', '', $control);
    }
    else {
        $control = str_replace('{lnkclass}', '', $control);
        $control = str_replace('{btnclass}', 'pbdn', $control);
    }
    echo $control;

?>

</div>
</div>
</div>
<div class="btf c253">
</div>
<script>dpth="/";!function(){for(var e=["js/jquery.4612ac.js","js/jqueryui.4612ac.js","js/menu.4612ac.js","js/menu-dropdown-animations.4612ac.js","js/menu-dropdown.c177e0.js","js/menu-dropdown-1.c177e0.js","js/shapes.4612ac.js","js/blog-index.c177e0.js"],n={},s=-1,t=function(t){var o=new XMLHttpRequest;o.open("GET",e[t],!0),o.onload=function(){for(n[t]=o.responseText;s<8&&void 0!==n[s+1];){s++;var e=document.createElement("script");e.textContent=n[s],document.body.appendChild(e)}},o.send()},o=0;o<8;o++)t(o)}();
</script>
</body>
</html>