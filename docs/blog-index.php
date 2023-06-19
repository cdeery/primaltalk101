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
#b{background-color:#fff}.v72{display:none;vertical-align:top}.v73{display:none;vertical-align:top}.v74{display:none;vertical-align:top;overflow:visible}.ps284{position:relative;margin-left:0;margin-top:0}.s303{min-width:237px;width:237px;min-height:84px;height:84px}.m11{padding:0px 0px 0px 0px}.v75{display:inline-block;vertical-align:top}.mcv11{display:inline-block}.s304{min-width:237px;width:237px;min-height:84px}.c269{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-image:url(images/hamburger-gray-84-1.png);background-color:transparent;background-repeat:no-repeat;background-position:50% 50%;background-size:contain}.webp .c269{background-image:url(images/hamburger-gray-84-1.webp)}.ps285{position:relative;margin-left:0;margin-top:32px}.s305{min-width:237px;width:237px;min-height:19px}.c270{pointer-events:auto;overflow:hidden;height:19px}.p32{text-indent:0;padding-bottom:0;padding-right:0;text-align:center}.f93{font-family:Lato;font-size:12px;font-size:calc(12px * var(--f));line-height:1.334;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:transparent;background-color:initial;text-shadow:none}.v76{display:none;vertical-align:top}.s306{min-width:182px;width:182px;min-height:351px;height:351px}.ml11{outline:0}.s307{min-width:182px;width:182px;min-height:174px;height:174px}.s308{min-width:182px;width:182px;min-height:174px}.c271{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps286{position:relative;margin-left:0;margin-top:43px}.s309{min-width:182px;width:182px;min-height:88px}.c272{pointer-events:auto;overflow:hidden;height:88px}.p33{text-indent:0;padding-bottom:0;padding-right:0;text-align:left}.f94{font-family:Lato;font-size:60px;font-size:calc(60px * var(--f));line-height:1.318;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps287{position:relative;margin-left:0;margin-top:3px}.c273{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps288{position:relative;margin-left:0;margin-top:43px}.ps289{position:relative;margin-left:0;margin-top:96px}.c274{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps290{position:relative;margin-left:0;margin-top:71px}.s310{min-width:182px;width:182px;min-height:31px}.c275{pointer-events:auto;overflow:hidden;height:31px}.f95{font-family:Lato;font-size:20px;font-size:calc(20px * var(--f));line-height:1.351;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps291{position:relative;margin-left:0;margin-top:96px}.c276{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps292{position:relative;margin-left:0;margin-top:71px}.c277{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.c278{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps293{position:relative;margin-top:15px}.v77{display:block;vertical-align:top}.s311{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:58px}.s312{min-width:310px;width:310px;min-height:58px}.c279{z-index:1;pointer-events:auto;overflow:hidden;height:58px}.f96{font-family:"Helvetica Neue", sans-serif;font-size:47px;font-size:calc(47px * var(--f));line-height:1.107;font-weight:300;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#000;background-color:initial;text-shadow:none}.v78{display:block;vertical-align:top}.ps294{position:relative;margin-top:0}.s313{width:100%;min-width:960px;min-height:76px}.c280{z-index:2;pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps295{display:inline-block;width:0;height:0}.ps296{position:relative;margin-top:20px}.s314{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:56px}.ps297{position:relative;margin-left:6px;margin-top:0}.s315{min-width:929px;width:929px;min-height:56px}.ps298{position:relative;margin-left:0;margin-top:4px}.s316{min-width:83px;width:83px;min-height:49px}.c282{z-index:3;pointer-events:auto}.f97{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:10px;padding-bottom:9px;margin-top:0;margin-bottom:0}.btn71{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn71:hover{background-color:#82939e;border-color:#000;color:#000}.btn71:active{background-color:#52646f;border-color:#000;color:#fff}.v79{display:inline-block;overflow:hidden;outline:0}.s317{width:83px;padding-right:0;height:30px}.ps299{position:relative;margin-left:22px;margin-top:4px}.c283{z-index:4;pointer-events:auto}.btn72{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn72:hover{background-color:#82939e;border-color:#000;color:#000}.btn72:active{background-color:#52646f;border-color:#000;color:#fff}.ps300{position:relative;margin-left:22px;margin-top:4px}.c284{z-index:5;pointer-events:auto}.btn73{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn73:hover{background-color:#82939e;border-color:#000;color:#000}.btn73:active{background-color:#52646f;border-color:#000;color:#fff}.ps301{position:relative;margin-left:23px;margin-top:4px}.c285{z-index:6;pointer-events:auto}.btn74{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn74:hover{background-color:#82939e;border-color:#000;color:#000}.btn74:active{background-color:#52646f;border-color:#000;color:#fff}.s318{min-width:114px;width:114px;min-height:49px}.c286{z-index:7;pointer-events:auto}.btn75{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn75:hover{background-color:#82939e;border-color:#000;color:#000}.btn75:active{background-color:#52646f;border-color:#000;color:#fff}.s319{width:114px;padding-right:0;height:30px}.ps302{position:relative;margin-left:22px;margin-top:0}.s320{min-width:170px;width:170px;min-height:56px}.c287{z-index:8;pointer-events:auto}.f98{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn76{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn76:hover{background-color:#82939e;border-color:#000;color:#000}.btn76:active{background-color:#52646f;border-color:#000;color:#fff}.s321{width:170px;padding-right:0;height:30px}.ps303{position:relative;margin-left:22px;margin-top:0}.s322{min-width:180px;width:180px;min-height:56px}.c288{z-index:9;pointer-events:auto}.btn77{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn77:hover{background-color:#82939e;border-color:#000;color:#000}.btn77:active{background-color:#52646f;border-color:#000;color:#fff}.s323{width:180px;padding-right:0;height:30px}.v80{display:inline-block;vertical-align:top;overflow:hidden}.ps304{position:relative;margin-top:109px}.s324{width:100%;min-width:960px}.c289{z-index:10;pointer-events:none}.ps305{position:relative;margin-top:0}.s325{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:2125px}.s326{width:100%;min-width:960px;min-height:1427px}.c290{z-index:11}.ps306{position:relative;margin-left:120px;margin-top:0}.s327{min-width:720px;width:720px;min-height:94px}.c291{z-index:12;pointer-events:auto;overflow:hidden;height:94px}.f99{font-family:Lato;font-size:36px;font-size:calc(36px * var(--f));line-height:1.307;font-weight:300;font-style:normal;text-decoration:underline;text-transform:none;letter-spacing:normal;color:#54387d;background-color:initial;text-shadow:none}.ps307{position:relative;margin-left:143px;margin-top:51px}.s328{min-width:730px;width:730px;min-height:410px;height:410px}.c292{z-index:13;pointer-events:auto}.i20{position:absolute;left:0;width:730px;height:410px;top:0;border:0}.i21{width:100%;height:100%;display:inline-block;-webkit-transform:translate3d(0,0,0)}.ps308{position:relative;margin-left:120px;margin-top:33px}.s329{min-width:753px;width:753px;min-height:839px}.c293{z-index:14;pointer-events:auto;overflow:hidden;height:839px}.f100{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.f101{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.ps309{position:relative;margin-top:100px}.s330{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:40px}.ps310{display:inline-block;position:relative;left:50%;-ms-transform:translate(-50%,0);-webkit-transform:translate(-50%,0);transform:translate(-50%,0)}.ps311{position:relative;margin-left:126px;margin-top:0}.s331{min-width:708px;width:708px;min-height:40px}.c294{z-index:32}.s332{min-width:48px;width:48px;min-height:40px}.c295{z-index:20;pointer-events:auto}.f102{font-family:"Helvetica Neue", sans-serif;font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn78{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn78:hover{background-color:#82939e;border-color:#000;color:#000}.btn78:active{background-color:#52646f;border-color:#000;color:#fff}.s333{width:48px;padding-right:0;height:14px}.ps312{position:relative;margin-left:12px;margin-top:0}.c296{z-index:21;pointer-events:auto}.btn79{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn79:hover{background-color:#82939e;border-color:#000;color:#000}.btn79:active{background-color:#52646f;border-color:#000;color:#fff}.c297{z-index:22;pointer-events:auto}.btn80{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn80:hover{background-color:#82939e;border-color:#000;color:#000}.btn80:active{background-color:#52646f;border-color:#000;color:#fff}.c298{z-index:23;pointer-events:auto}.btn81{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn81:hover{background-color:#82939e;border-color:#000;color:#000}.btn81:active{background-color:#52646f;border-color:#000;color:#fff}.c299{z-index:24;pointer-events:auto}.btn82{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn82:hover{background-color:#82939e;border-color:#000;color:#000}.btn82:active{background-color:#52646f;border-color:#000;color:#fff}.c300{z-index:25;pointer-events:auto}.btn83{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn83:hover{background-color:#82939e;border-color:#000;color:#000}.btn83:active{background-color:#52646f;border-color:#000;color:#fff}.c301{z-index:26;pointer-events:auto}.btn84{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn84:hover{background-color:#82939e;border-color:#000;color:#000}.btn84:active{background-color:#52646f;border-color:#000;color:#fff}.c302{z-index:27;pointer-events:auto}.btn85{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn85:hover{background-color:#82939e;border-color:#000;color:#000}.btn85:active{background-color:#52646f;border-color:#000;color:#fff}.c303{z-index:28;pointer-events:auto}.btn86{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn86:hover{background-color:#82939e;border-color:#000;color:#000}.btn86:active{background-color:#52646f;border-color:#000;color:#fff}.c304{z-index:29;pointer-events:auto}.btn87{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn87:hover{background-color:#82939e;border-color:#000;color:#000}.btn87:active{background-color:#52646f;border-color:#000;color:#fff}.c305{z-index:30;pointer-events:auto}.btn88{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn88:hover{background-color:#82939e;border-color:#000;color:#000}.btn88:active{background-color:#52646f;border-color:#000;color:#fff}.c306{z-index:31;pointer-events:auto}.btn89{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn89:hover{background-color:#82939e;border-color:#000;color:#000}.btn89:active{background-color:#52646f;border-color:#000;color:#fff}body{--d:0;--s:960}@media (min-width:768px) and (max-width:959px) {.s303{min-width:190px;width:190px;min-height:67px;height:67px}.s304{min-width:190px;width:190px;min-height:67px}.c269{background-image:url(images/hamburger-gray-67.png)}.webp .c269{background-image:url(images/hamburger-gray-67.webp)}.ps285{margin-top:26px}.s305{min-width:190px;width:190px;min-height:15px}.c270{height:15px}.f93{font-size:9px;font-size:calc(9px * var(--f))}.s306{min-width:146px;width:146px;min-height:280px;height:280px}.s307{min-width:146px;width:146px;min-height:139px;height:139px}.s308{min-width:146px;width:146px;min-height:139px}.ps286{margin-top:34px}.s309{min-width:146px;width:146px;min-height:71px}.c272{height:71px}.f94{font-size:48px;font-size:calc(48px * var(--f));line-height:1.313}.ps287{margin-top:2px}.ps288{margin-top:34px}.ps289{margin-top:76px}.ps290{margin-top:54px}.s310{min-width:146px;width:146px}.ps291{margin-top:76px}.ps292{margin-top:54px}.ps293{margin-top:12px}.s311{min-width:768px;width:768px;min-height:46px}.s312{min-width:248px;width:248px;min-height:46px}.c279{height:46px}.f96{font-size:37px;font-size:calc(37px * var(--f));line-height:1.109}.s313{min-width:768px;min-height:61px}.ps296{margin-top:16px}.s314{min-width:768px;width:768px;min-height:45px}.ps297{margin-left:5px}.s315{min-width:743px;width:743px;min-height:45px}.s316{min-width:66px;width:66px;min-height:39px}.f97{font-size:20px;font-size:calc(20px * var(--f));padding-top:8px;padding-bottom:7px}.s317{width:66px;height:24px}.ps299{margin-left:18px}.ps300{margin-left:18px}.ps301{margin-left:19px}.s318{min-width:91px;width:91px;min-height:39px}.s319{width:91px;height:24px}.ps302{margin-left:17px}.s320{min-width:136px;width:136px;min-height:45px}.f98{font-size:20px;font-size:calc(20px * var(--f));padding-top:11px;padding-bottom:10px}.s321{width:136px;height:24px}.ps303{margin-left:18px}.s322{min-width:144px;width:144px;min-height:45px}.s323{width:144px;height:24px}.ps304{margin-top:87px}.s324{min-width:768px}.s325{min-width:768px;width:768px;min-height:1701px}.s326{min-width:768px;min-height:1142px}.ps306{margin-left:96px}.s327{min-width:576px;width:576px;min-height:75px}.c291{height:75px}.f99{font-size:28px;font-size:calc(28px * var(--f));line-height:1.322}.ps307{margin-left:114px;margin-top:41px}.s328{min-width:584px;width:584px;min-height:328px;height:328px}.i20{width:584px;height:328px}.ps308{margin-left:96px;margin-top:27px}.s329{min-width:602px;width:602px;min-height:671px}.c293{height:671px}.f100{font-size:12px;font-size:calc(12px * var(--f));line-height:1.751}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:12px;font-size:calc(12px * var(--f))}.ps309{margin-top:80px}.s330{min-width:768px;width:768px;min-height:32px}.ps311{margin-left:101px}.s331{min-width:566px;width:566px;min-height:32px}.s332{min-width:38px;width:38px;min-height:32px}.f102{font-size:9px;font-size:calc(9px * var(--f));line-height:1.223;padding-top:11px;padding-bottom:10px}.s333{width:38px;height:11px}.ps312{margin-left:10px}body{--d:1;--s:768}}@media (min-width:480px) and (max-width:767px) {.ps281{position:relative;margin-top:63px}.v72{display:block}.s300{pointer-events:none;min-width:480px;width:480px;margin-left:auto;margin-right:auto;min-height:56px}.v73{display:inline-block}.ps282{position:relative;margin-left:0;margin-top:0}.s301{min-width:480px;width:480px;min-height:56px;height:56px}.c267{z-index:19;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v74{display:inline-block}.ps283{position:relative;margin-left:5px;margin-top:8px}.s302{min-width:119px;width:119px;min-height:42px;height:42px}.c268{pointer-events:auto}.s303{min-width:119px;width:119px;min-height:42px;height:42px}.s304{min-width:119px;width:119px;min-height:42px}.c269{background-image:url(images/hamburger-gray-42.png)}.webp .c269{background-image:url(images/hamburger-gray-42.webp)}.ps285{margin-top:16px}.s305{min-width:119px;width:119px;min-height:10px}.c270{height:10px}.f93{font-size:6px;font-size:calc(6px * var(--f))}.s306{min-width:401px;width:401px;min-height:530px;height:530px}.s307{min-width:401px;width:401px;min-height:87px;height:87px}.s308{min-width:401px;width:401px;min-height:87px}.ps286{margin-top:21px}.s309{min-width:401px;width:401px;min-height:46px}.c272{height:46px}.f94{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps287{margin-top:2px}.ps288{margin-top:20px}.ps289{margin-top:1px}.ps290{margin-top:21px}.s310{min-width:401px;width:401px;min-height:46px}.c275{height:46px}.f95{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps291{margin-top:2px}.ps292{margin-top:20px}.ps293{margin-top:-111px}.s311{min-width:480px;width:480px;min-height:56px}.s312{min-width:270px;width:270px;min-height:56px}.c279{height:56px}.f96{font-size:40px;font-size:calc(40px * var(--f));line-height:1.126}.v78{display:none}.ps296{margin-top:0}.s314{min-width:480px;width:480px;min-height:29px}.ps297{margin-left:3px}.s315{min-width:465px;width:465px;min-height:29px}.ps298{margin-top:2px}.s316{min-width:42px;width:42px;min-height:24px}.f97{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:5px;padding-bottom:5px}.s317{width:42px;height:14px}.ps299{margin-left:11px;margin-top:2px}.ps300{margin-left:10px;margin-top:2px}.ps301{margin-left:11px;margin-top:2px}.s318{min-width:57px;width:57px;min-height:24px}.s319{width:57px;height:14px}.ps302{margin-left:12px}.s320{min-width:86px;width:86px;min-height:29px}.f98{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:8px;padding-bottom:7px}.s321{width:86px;height:14px}.ps303{margin-left:10px}.s322{min-width:90px;width:90px;min-height:29px}.s323{width:90px;height:14px}.ps304{margin-top:170px}.s324{min-width:480px}.s325{min-width:480px;width:480px;min-height:5017px}.s326{min-width:480px;min-height:3048px}.ps306{margin-left:26px}.s327{min-width:428px;width:428px;min-height:104px}.c291{height:104px}.f99{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps307{margin-left:26px;margin-top:31px}.s328{min-width:428px;width:428px;min-height:261px;height:261px}.i20{width:428px;height:240px;top:11px}.ps308{margin-left:26px}.s329{min-width:438px;width:438px;min-height:2619px}.c293{height:2619px}.f100{font-size:24px;font-size:calc(24px * var(--f))}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:24px;font-size:calc(24px * var(--f));line-height:1.626}.ps309{margin-top:-4132px}.s330{min-width:480px;width:480px;min-height:20px}.ps311{margin-left:63px}.s331{min-width:354px;width:354px;min-height:20px}.s332{min-width:24px;width:24px;min-height:20px}.f102{font-size:6px;font-size:calc(6px * var(--f));padding-top:7px;padding-bottom:6px}.s333{width:24px;height:7px}.ps312{margin-left:6px}body{--d:2;--s:480}}@media (max-width:479px) {.ps281{position:relative;margin-top:42px}.v72{display:block}.s300{pointer-events:none;min-width:320px;width:320px;margin-left:auto;margin-right:auto;min-height:37px}.v73{display:inline-block}.ps282{position:relative;margin-left:0;margin-top:0}.s301{min-width:320px;width:320px;min-height:37px;height:37px}.c267{z-index:19;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v74{display:inline-block}.ps283{position:relative;margin-left:3px;margin-top:5px}.s302{min-width:79px;width:79px;min-height:28px;height:28px}.c268{pointer-events:auto}.s303{min-width:79px;width:79px;min-height:28px;height:28px}.s304{min-width:79px;width:79px;min-height:28px}.c269{background-image:url(images/hamburger-gray-28.png)}.webp .c269{background-image:url(images/hamburger-gray-28.webp)}.ps285{margin-top:10px}.s305{min-width:79px;width:79px;min-height:7px}.c270{height:7px}.f93{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251}.s306{min-width:267px;width:267px;min-height:353px;height:353px}.s307{min-width:267px;width:267px;min-height:58px;height:58px}.s308{min-width:267px;width:267px;min-height:58px}.ps286{margin-top:14px}.s309{min-width:267px;width:267px;min-height:31px}.c272{height:31px}.f94{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps287{margin-top:1px}.ps288{margin-top:14px}.ps289{margin-top:1px}.ps290{margin-top:14px}.s310{min-width:267px;width:267px}.ps291{margin-top:1px}.ps292{margin-top:14px}.ps293{margin-top:-74px}.s311{min-width:320px;width:320px;min-height:37px}.s312{min-width:180px;width:180px;min-height:37px}.c279{height:37px}.f96{font-size:27px;font-size:calc(27px * var(--f));line-height:1.112}.v78{display:none}.ps296{margin-top:0}.s314{min-width:320px;width:320px;min-height:19px}.ps297{margin-left:2px}.s315{min-width:310px;width:310px;min-height:19px}.ps298{margin-top:1px}.s316{min-width:28px;width:28px;min-height:16px}.f97{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:3px;padding-bottom:3px}.s317{width:28px;height:10px}.ps299{margin-left:7px;margin-top:1px}.ps300{margin-left:7px;margin-top:1px}.ps301{margin-left:7px;margin-top:1px}.s318{min-width:38px;width:38px;min-height:16px}.s319{width:38px;height:10px}.ps302{margin-left:8px}.s320{min-width:57px;width:57px;min-height:19px}.f98{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:5px;padding-bottom:4px}.s321{width:57px;height:10px}.ps303{margin-left:7px}.s322{min-width:60px;width:60px;min-height:19px}.s323{width:60px;height:10px}.ps304{margin-top:114px}.s324{min-width:320px}.s325{min-width:320px;width:320px;min-height:3345px}.s326{min-width:320px;min-height:2032px}.ps306{margin-left:17px}.s327{min-width:285px;width:285px;min-height:69px}.c291{height:69px}.f99{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps307{margin-left:17px;margin-top:21px}.s328{min-width:285px;width:285px;min-height:174px;height:174px}.i20{width:285px;height:160px;top:7px}.ps308{margin-left:17px;margin-top:22px}.s329{min-width:292px;width:292px;min-height:1746px}.c293{height:1746px}.f100{font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.ps309{margin-top:-2754px}.s330{min-width:320px;width:320px;min-height:13px}.ps311{margin-left:42px}.s331{min-width:236px;width:236px;min-height:13px}.s332{min-width:16px;width:16px;min-height:13px}.f102{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251;padding-top:4px;padding-bottom:4px}.s333{width:16px;height:5px}.ps312{margin-left:4px}body{--d:3;--s:320}}@media (-webkit-min-device-pixel-ratio:1.7), (min-resolution:144dpi) {.c269{background-image:url(images/hamburger-gray-168.png);background-size:contain}.webp .c269{background-image:url(images/hamburger-gray-168.webp)}}@media (min-width:768px) and (max-width:959px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:768px) and (max-width:959px) and (min-resolution:144dpi) {.c269{background-image:url(images/hamburger-gray-134.png);background-size:contain}.webp .c269{background-image:url(images/hamburger-gray-134.webp)}}@media (min-width:480px) and (max-width:767px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:480px) and (max-width:767px) and (min-resolution:144dpi) {.c269{background-image:url(images/hamburger-gray-84.png);background-size:contain}.webp .c269{background-image:url(images/hamburger-gray-84.webp)}}@media (max-width:479px) and (-webkit-min-device-pixel-ratio:1.7), (max-width:479px) and (min-resolution:144dpi) {.c269{background-image:url(images/hamburger-gray-56.png);background-size:contain}.webp .c269{background-image:url(images/hamburger-gray-56.webp)}}</style>
<script>!function(){var A=new Image;A.onload=A.onerror=function(){1!=A.height&&document.body.classList.remove("webp")},A.src="data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAD8D+JaQAA3AA/ua1AAA"}();
</script>
<link onload="this.media='all';this.onload=null;" rel="stylesheet" href="css/site.8c8984.css" media="print">
</head>
<body class="webp" id="b">
<script>var p=document.createElement("P");p.innerHTML="&nbsp;",p.style.cssText="position:fixed;visible:hidden;font-size:100px;zoom:1",document.body.appendChild(p);var rsz=function(e){return function(){var r=Math.trunc(1e3/parseFloat(window.getComputedStyle(e).getPropertyValue("font-size")))/10,t=document.body;r!=t.style.getPropertyValue("--f")&&t.style.setProperty("--f",r)}}(p);if("ResizeObserver"in window){var ro=new ResizeObserver(rsz);ro.observe(p)}else if("requestAnimationFrame"in window){var raf=function(){rsz(),requestAnimationFrame(raf)};requestAnimationFrame(raf)}else setInterval(rsz,100);</script>

<script>!function(){var e=function(){var e=document.body,n=window.innerWidth,t=getComputedStyle(e).getPropertyValue("--s");n=320==t?Math.min(479,n):480==t?Math.min(610,n):t,e.style.setProperty("--z",Math.trunc(n/t*1000)/1000)};window.addEventListener?window.addEventListener("resize",e,!0):window.onscroll=e,e()}();</script>

<div class="ps281 v72 s300">
<div class="v73 ps282 s301 c267">
<div class="v74 ps283 s302 c268">
<ul class="menu-dropdown v73 ps284 s303 m11" id="m1">
<li class="v75 ps284 s303 mit11">
<div class="menu-content mcv11">
<div class="v75 ps284 s304 c269">
<div class="v75 ps285 s305 c270">
<p class="p32 f93">Menu</p>
</div>
</div>
</div>
<ul class="menu-dropdown-1 v76 ps284 s306 m11">
<li class="v75 ps284 s307 mit11">
<a href="./" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c271"><div class="v75 ps286 s309 c272"><p class="p33 f94">Home</p></div></div></div></a>
</li>
<li class="v75 ps287 s307 mit11">
<a href="about.html" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c273"><div class="v75 ps288 s309 c272"><p class="p33 f94">About</p></div></div></div></a>
</li>
<li class="v73 ps289 s307 mit11">
<a href="media.html" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c274"><div class="v75 ps290 s310 c275"><p class="p33 f95">Media</p></div></div></div></a>
</li>
<li class="v73 ps291 s307 mit11">
<a href="contact.html" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c276"><div class="v75 ps292 s310 c275"><p class="p33 f95">Contact</p></div></div></div></a>
</li>
<li class="v73 ps289 s307 mit11">
<a href="special-offer.html" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c277"><div class="v75 ps290 s310 c275"><p class="p33 f95">Special Offer</p></div></div></div></a>
</li>
<li class="v73 ps291 s307 mit11">
<a href="work-with-me.html" class="ml11"><div class="menu-content mcv11"><div class="v75 ps284 s308 c278"><div class="v75 ps292 s310 c275"><p class="p33 f95">Work with me!</p></div></div></div></a>
</li>
</ul>
</li>
</ul>
</div>
</div>
</div>
<div class="ps293 v77 s311">
<div class="v75 ps284 s312 c279">
<p class="p33 f96"><a href="#">PrimalTalk101</a></p>
</div>
</div>
<div class="v78 ps294 s313 c280">
<div class="ps295">
</div>
<div class="ps296 v77 s314">
<div class="v75 ps297 s315 c281">
<div class="v75 ps298 s316 c282">
<a href="./" class="f97 btn71 v79 s317">Home</a>
</div>
<div class="v75 ps299 s316 c283">
<a href="about.html" class="f97 btn72 v79 s317">About</a>
</div>
<div class="v75 ps300 s316 c284">
<a href="media.html" class="f97 btn73 v79 s317">Media</a>
</div>
<div class="v75 ps301 s316 c285">
<a href="#" class="f97 btn74 v79 s317">Blog</a>
</div>
<div class="v75 ps300 s318 c286">
<a href="contact.html" class="f97 btn75 v79 s319">Contact</a>
</div>
<div class="v75 ps302 s320 c287">
<a href="special-offer.html" class="f98 btn76 v79 s321">Special Offer</a>
</div>
<div class="v75 ps303 s322 c288">
<a href="work-with-me.html" class="f98 btn77 v79 s323">Work with me!</a>
</div>
</div>
</div>
</div>
<div class="v80 ps304 s324 c289">
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
<div class="ps309 v77 s330">
<div class="v75 ps311 s331 c294">
<div class="ps310">
<?php

    echo '<style>.pbdn{display:none}.pbc{border: 0;background-color:#c0c0c0;color:#fff;border-color:#677a85}</style>';
    $control = '<div class="v75 ps284 s332 c295 {btnclass}"><a href="#" class="f102 btn78 v79 s333 {lnkclass}">&lt;&lt;</a></div>';
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

    $control = '<div class="v75 ps312 s332 c296 {btnclass}"><a href="#" class="f102 btn79 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c297 {btnclass}"><a href="#" class="f102 btn80 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c298 {btnclass}"><a href="#" class="f102 btn81 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c299 {btnclass}"><a href="#" class="f102 btn82 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c300 {btnclass}"><a href="#" class="f102 btn83 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c301 {btnclass}"><a href="#" class="f102 btn84 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c302 {btnclass}"><a href="#" class="f102 btn85 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c303 {btnclass}"><a href="#" class="f102 btn86 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c304 {btnclass}"><a href="#" class="f102 btn87 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c305 {btnclass}"><a href="#" class="f102 btn88 v79 s333 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v75 ps312 s332 c306 {btnclass}"><a href="#" class="f102 btn89 v79 s333 {lnkclass}">&gt;&gt;</a></div>';
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
<div class="btf c266">
</div>
<script>dpth="/";!function(){for(var e=["js/jquery.4612ac.js","js/jqueryui.4612ac.js","js/menu.4612ac.js","js/menu-dropdown-animations.4612ac.js","js/menu-dropdown.8c8984.js","js/menu-dropdown-1.8c8984.js","js/shapes.4612ac.js","js/blog-index.8c8984.js"],n={},s=-1,t=function(t){var o=new XMLHttpRequest;o.open("GET",e[t],!0),o.onload=function(){for(n[t]=o.responseText;s<8&&void 0!==n[s+1];){s++;var e=document.createElement("script");e.textContent=n[s],document.body.appendChild(e)}},o.send()},o=0;o<8;o++)t(o)}();
</script>
</body>
</html>