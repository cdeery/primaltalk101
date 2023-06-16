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
#b{background-color:#fff}.ps269{position:relative;margin-top:15px}.v80{display:block;vertical-align:top}.s294{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:58px}.v81{display:inline-block;vertical-align:top}.ps270{position:relative;margin-left:0;margin-top:0}.s295{min-width:310px;width:310px;min-height:58px}.c259{z-index:1;pointer-events:auto;overflow:hidden;height:58px}.p35{text-indent:0;padding-bottom:0;padding-right:0;text-align:left}.f84{font-family:"Helvetica Neue", sans-serif;font-size:47px;font-size:calc(47px * var(--f));line-height:1.107;font-weight:300;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#000;background-color:initial;text-shadow:none}.v82{display:block;vertical-align:top}.ps271{position:relative;margin-top:9px}.s296{width:100%;min-width:960px;min-height:76px}.c260{z-index:2;pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps272{display:inline-block;width:0;height:0}.ps273{position:relative;margin-top:20px}.s297{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:56px}.ps274{position:relative;margin-left:6px;margin-top:0}.s298{min-width:929px;width:929px;min-height:56px}.ps275{position:relative;margin-left:0;margin-top:4px}.s299{min-width:83px;width:83px;min-height:49px}.c262{z-index:3;pointer-events:auto}.f85{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:10px;padding-bottom:9px;margin-top:0;margin-bottom:0}.btn71{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn71:hover{background-color:#82939e;border-color:#000;color:#000}.btn71:active{background-color:#52646f;border-color:#000;color:#fff}.v83{display:inline-block;overflow:hidden;outline:0}.s300{width:83px;padding-right:0;height:30px}.ps276{position:relative;margin-left:22px;margin-top:4px}.c263{z-index:4;pointer-events:auto}.btn72{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn72:hover{background-color:#82939e;border-color:#000;color:#000}.btn72:active{background-color:#52646f;border-color:#000;color:#fff}.ps277{position:relative;margin-left:22px;margin-top:4px}.c264{z-index:5;pointer-events:auto}.btn73{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn73:hover{background-color:#82939e;border-color:#000;color:#000}.btn73:active{background-color:#52646f;border-color:#000;color:#fff}.ps278{position:relative;margin-left:23px;margin-top:4px}.c265{z-index:6;pointer-events:auto}.btn74{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn74:hover{background-color:#82939e;border-color:#000;color:#000}.btn74:active{background-color:#52646f;border-color:#000;color:#fff}.s301{min-width:114px;width:114px;min-height:49px}.c266{z-index:7;pointer-events:auto}.btn75{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn75:hover{background-color:#82939e;border-color:#000;color:#000}.btn75:active{background-color:#52646f;border-color:#000;color:#fff}.s302{width:114px;padding-right:0;height:30px}.ps279{position:relative;margin-left:22px;margin-top:0}.s303{min-width:170px;width:170px;min-height:56px}.c267{z-index:8;pointer-events:auto}.f86{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn76{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn76:hover{background-color:#82939e;border-color:#000;color:#000}.btn76:active{background-color:#52646f;border-color:#000;color:#fff}.s304{width:170px;padding-right:0;height:30px}.ps280{position:relative;margin-left:22px;margin-top:0}.s305{min-width:180px;width:180px;min-height:56px}.c268{z-index:9;pointer-events:auto}.btn77{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn77:hover{background-color:#82939e;border-color:#000;color:#000}.btn77:active{background-color:#52646f;border-color:#000;color:#fff}.s306{width:180px;padding-right:0;height:30px}.ps281{position:relative;margin-top:4px}.s307{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:192px}.s308{min-width:960px;width:960px;min-height:192px}.w11{line-height:0}.s309{min-width:960px;width:960px;min-height:123px}.c269{z-index:19;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v84{display:inline-block;vertical-align:top;overflow:visible}.ps282{position:relative;margin-left:0;margin-top:-105px}.s310{min-width:367px;width:367px;min-height:174px;height:174px}.c270{z-index:33;pointer-events:auto}.s311{min-width:0;width:0;min-height:0;height:0}.m21{padding:0px 0px 0px 0px}.v85{display:inline-block;vertical-align:top}.s312{min-width:367px;width:367px;min-height:174px;height:174px}.m22{z-index:9999;padding:0px 0px 0px 0px}.ml11{outline:0}.s313{min-width:182px;width:182px;min-height:174px;height:174px}.mcv15{display:inline-block}.s314{min-width:182px;width:182px;min-height:174px}.c271{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps283{position:relative;margin-left:0;margin-top:43px}.s315{min-width:182px;width:182px;min-height:88px}.c272{pointer-events:auto;overflow:hidden;height:88px}.p36{text-indent:0;padding-bottom:0;padding-right:0;text-align:center}.f87{font-family:Lato;font-size:60px;font-size:calc(60px * var(--f));line-height:1.318;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps284{position:relative;margin-left:3px;margin-top:0}.c273{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps285{position:relative;margin-left:0;margin-top:43px}.v86{display:none;vertical-align:top}.ps286{position:relative;margin-left:188px;margin-top:0}.c274{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps287{position:relative;margin-left:0;margin-top:71px}.s316{min-width:182px;width:182px;min-height:31px}.c275{pointer-events:auto;overflow:hidden;height:31px}.f88{font-family:Lato;font-size:20px;font-size:calc(20px * var(--f));line-height:1.351;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps288{position:relative;margin-left:188px;margin-top:0}.c276{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps289{position:relative;margin-left:0;margin-top:71px}.c277{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.c278{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v87{display:inline-block;vertical-align:top;overflow:hidden}.ps290{position:relative;margin-top:-96px}.s317{width:100%;min-width:960px}.c279{z-index:10;pointer-events:none}.ps291{position:relative;margin-top:0}.s318{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:2125px}.s319{width:100%;min-width:960px;min-height:1427px}.c280{z-index:11}.ps292{position:relative;margin-left:120px;margin-top:0}.s320{min-width:720px;width:720px;min-height:94px}.c281{z-index:12;pointer-events:auto;overflow:hidden;height:94px}.f89{font-family:Lato;font-size:36px;font-size:calc(36px * var(--f));line-height:1.307;font-weight:300;font-style:normal;text-decoration:underline;text-transform:none;letter-spacing:normal;color:#54387d;background-color:initial;text-shadow:none}.ps293{position:relative;margin-left:143px;margin-top:51px}.s321{min-width:730px;width:730px;min-height:410px;height:410px}.c282{z-index:13;pointer-events:auto}.i18{position:absolute;left:0;width:730px;height:410px;top:0;border:0}.i19{width:100%;height:100%;display:inline-block;-webkit-transform:translate3d(0,0,0)}.ps294{position:relative;margin-left:120px;margin-top:33px}.s322{min-width:753px;width:753px;min-height:839px}.c283{z-index:14;pointer-events:auto;overflow:hidden;height:839px}.f90{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.f91{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.ps295{position:relative;margin-top:100px}.s323{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:40px}.ps296{display:inline-block;position:relative;left:50%;-ms-transform:translate(-50%,0);-webkit-transform:translate(-50%,0);transform:translate(-50%,0)}.ps297{position:relative;margin-left:126px;margin-top:0}.s324{min-width:708px;width:708px;min-height:40px}.c284{z-index:32}.s325{min-width:48px;width:48px;min-height:40px}.c285{z-index:20;pointer-events:auto}.f92{font-family:"Helvetica Neue", sans-serif;font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn78{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn78:hover{background-color:#82939e;border-color:#000;color:#000}.btn78:active{background-color:#52646f;border-color:#000;color:#fff}.s326{width:48px;padding-right:0;height:14px}.ps298{position:relative;margin-left:12px;margin-top:0}.c286{z-index:21;pointer-events:auto}.btn79{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn79:hover{background-color:#82939e;border-color:#000;color:#000}.btn79:active{background-color:#52646f;border-color:#000;color:#fff}.c287{z-index:22;pointer-events:auto}.btn80{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn80:hover{background-color:#82939e;border-color:#000;color:#000}.btn80:active{background-color:#52646f;border-color:#000;color:#fff}.c288{z-index:23;pointer-events:auto}.btn81{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn81:hover{background-color:#82939e;border-color:#000;color:#000}.btn81:active{background-color:#52646f;border-color:#000;color:#fff}.c289{z-index:24;pointer-events:auto}.btn82{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn82:hover{background-color:#82939e;border-color:#000;color:#000}.btn82:active{background-color:#52646f;border-color:#000;color:#fff}.c290{z-index:25;pointer-events:auto}.btn83{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn83:hover{background-color:#82939e;border-color:#000;color:#000}.btn83:active{background-color:#52646f;border-color:#000;color:#fff}.c291{z-index:26;pointer-events:auto}.btn84{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn84:hover{background-color:#82939e;border-color:#000;color:#000}.btn84:active{background-color:#52646f;border-color:#000;color:#fff}.c292{z-index:27;pointer-events:auto}.btn85{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn85:hover{background-color:#82939e;border-color:#000;color:#000}.btn85:active{background-color:#52646f;border-color:#000;color:#fff}.c293{z-index:28;pointer-events:auto}.btn86{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn86:hover{background-color:#82939e;border-color:#000;color:#000}.btn86:active{background-color:#52646f;border-color:#000;color:#fff}.c294{z-index:29;pointer-events:auto}.btn87{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn87:hover{background-color:#82939e;border-color:#000;color:#000}.btn87:active{background-color:#52646f;border-color:#000;color:#fff}.c295{z-index:30;pointer-events:auto}.btn88{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn88:hover{background-color:#82939e;border-color:#000;color:#000}.btn88:active{background-color:#52646f;border-color:#000;color:#fff}.c296{z-index:31;pointer-events:auto}.btn89{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn89:hover{background-color:#82939e;border-color:#000;color:#000}.btn89:active{background-color:#52646f;border-color:#000;color:#fff}body{--d:0;--s:960}@media (min-width:768px) and (max-width:959px) {.ps269{margin-top:12px}.s294{min-width:768px;width:768px;min-height:46px}.s295{min-width:248px;width:248px;min-height:46px}.c259{height:46px}.f84{font-size:37px;font-size:calc(37px * var(--f));line-height:1.109}.ps271{margin-top:8px}.s296{min-width:768px;min-height:61px}.ps273{margin-top:16px}.s297{min-width:768px;width:768px;min-height:45px}.ps274{margin-left:5px}.s298{min-width:743px;width:743px;min-height:45px}.ps275{margin-top:3px}.s299{min-width:66px;width:66px;min-height:39px}.f85{font-size:20px;font-size:calc(20px * var(--f));padding-top:8px;padding-bottom:7px}.s300{width:66px;height:24px}.ps276{margin-left:18px;margin-top:3px}.ps277{margin-left:18px;margin-top:3px}.ps278{margin-left:19px;margin-top:3px}.s301{min-width:91px;width:91px;min-height:39px}.s302{width:91px;height:24px}.ps279{margin-left:17px}.s303{min-width:136px;width:136px;min-height:45px}.f86{font-size:20px;font-size:calc(20px * var(--f));padding-top:11px;padding-bottom:10px}.s304{width:136px;height:24px}.ps280{margin-left:18px}.s305{min-width:144px;width:144px;min-height:45px}.s306{width:144px;height:24px}.ps281{margin-top:3px}.s307{min-width:768px;width:768px;min-height:153px}.s308{min-width:768px;width:768px;min-height:153px}.s309{min-width:768px;width:768px;min-height:98px}.ps282{margin-top:-84px}.s310{min-width:294px;width:294px;min-height:139px;height:139px}.s312{min-width:294px;width:294px;min-height:139px;height:139px}.s313{min-width:146px;width:146px;min-height:139px;height:139px}.s314{min-width:146px;width:146px;min-height:139px}.ps283{margin-top:34px}.s315{min-width:146px;width:146px;min-height:71px}.c272{height:71px}.f87{font-size:48px;font-size:calc(48px * var(--f));line-height:1.313}.ps284{margin-left:2px}.ps285{margin-top:34px}.ps286{margin-left:150px}.ps287{margin-top:54px}.s316{min-width:146px;width:146px}.ps288{margin-left:150px}.ps289{margin-top:54px}.ps290{margin-top:-77px}.s317{min-width:768px}.s318{min-width:768px;width:768px;min-height:1701px}.s319{min-width:768px;min-height:1142px}.ps292{margin-left:96px}.s320{min-width:576px;width:576px;min-height:75px}.c281{height:75px}.f89{font-size:28px;font-size:calc(28px * var(--f));line-height:1.322}.ps293{margin-left:114px;margin-top:41px}.s321{min-width:584px;width:584px;min-height:328px;height:328px}.i18{width:584px;height:328px}.ps294{margin-left:96px;margin-top:27px}.s322{min-width:602px;width:602px;min-height:671px}.c283{height:671px}.f90{font-size:12px;font-size:calc(12px * var(--f));line-height:1.751}.f91{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:12px;font-size:calc(12px * var(--f))}.ps295{margin-top:80px}.s323{min-width:768px;width:768px;min-height:32px}.ps297{margin-left:101px}.s324{min-width:566px;width:566px;min-height:32px}.s325{min-width:38px;width:38px;min-height:32px}.f92{font-size:9px;font-size:calc(9px * var(--f));line-height:1.223;padding-top:11px;padding-bottom:10px}.s326{width:38px;height:11px}.ps298{margin-left:10px}body{--d:1;--s:768}}@media (min-width:480px) and (max-width:767px) {.ps269{margin-top:8px}.s294{min-width:480px;width:480px;min-height:56px}.s295{min-width:270px;width:270px;min-height:56px}.c259{height:56px}.f84{font-size:40px;font-size:calc(40px * var(--f));line-height:1.126}.v82{display:none}.ps273{margin-top:0}.s297{min-width:480px;width:480px;min-height:29px}.ps274{margin-left:3px}.s298{min-width:465px;width:465px;min-height:29px}.ps275{margin-top:2px}.s299{min-width:42px;width:42px;min-height:24px}.f85{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:5px;padding-bottom:5px}.s300{width:42px;height:14px}.ps276{margin-left:11px;margin-top:2px}.ps277{margin-left:10px;margin-top:2px}.ps278{margin-left:11px;margin-top:2px}.s301{min-width:57px;width:57px;min-height:24px}.s302{width:57px;height:14px}.ps279{margin-left:12px}.s303{min-width:86px;width:86px;min-height:29px}.f86{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:8px;padding-bottom:7px}.s304{width:86px;height:14px}.ps280{margin-left:10px}.s305{min-width:90px;width:90px;min-height:29px}.s306{width:90px;height:14px}.ps281{margin-top:17px}.s307{min-width:480px;width:480px;min-height:62px}.s308{min-width:480px;width:480px;min-height:62px}.s309{min-width:480px;width:480px;min-height:62px}.ps282{margin-top:-53px}.s310{min-width:119px;width:119px;min-height:42px;height:42px}.s311{min-width:119px;width:119px;min-height:42px;height:42px}.v85{display:none}.s312{min-width:401px;width:401px;min-height:530px;height:530px}.s313{min-width:401px;width:401px;min-height:87px;height:87px}.s314{min-width:401px;width:401px;min-height:87px}.ps283{margin-top:21px}.s315{min-width:401px;width:401px;min-height:46px}.c272{height:46px}.f87{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps284{margin-left:0;margin-top:2px}.ps285{margin-top:20px}.v86{display:inline-block}.ps286{margin-left:0;margin-top:1px}.ps287{margin-top:21px}.s316{min-width:401px;width:401px;min-height:46px}.c275{height:46px}.f88{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps288{margin-left:0;margin-top:2px}.ps289{margin-top:20px}.ps290{margin-top:91px}.s317{min-width:480px}.s318{min-width:480px;width:480px;min-height:5017px}.s319{min-width:480px;min-height:3048px}.ps292{margin-left:26px}.s320{min-width:428px;width:428px;min-height:104px}.c281{height:104px}.f89{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps293{margin-left:26px;margin-top:31px}.s321{min-width:428px;width:428px;min-height:261px;height:261px}.i18{width:428px;height:240px;top:11px}.ps294{margin-left:26px}.s322{min-width:438px;width:438px;min-height:2619px}.c283{height:2619px}.f90{font-size:24px;font-size:calc(24px * var(--f))}.f91{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:24px;font-size:calc(24px * var(--f));line-height:1.626}.ps295{margin-top:-4132px}.s323{min-width:480px;width:480px;min-height:20px}.ps297{margin-left:63px}.s324{min-width:354px;width:354px;min-height:20px}.s325{min-width:24px;width:24px;min-height:20px}.f92{font-size:6px;font-size:calc(6px * var(--f));padding-top:7px;padding-bottom:6px}.s326{width:24px;height:7px}.ps298{margin-left:6px}body{--d:2;--s:480}}@media (max-width:479px) {.ps269{margin-top:5px}.s294{min-width:320px;width:320px;min-height:37px}.s295{min-width:180px;width:180px;min-height:37px}.c259{height:37px}.f84{font-size:27px;font-size:calc(27px * var(--f));line-height:1.112}.v82{display:none}.ps273{margin-top:0}.s297{min-width:320px;width:320px;min-height:19px}.ps274{margin-left:2px}.s298{min-width:310px;width:310px;min-height:19px}.ps275{margin-top:1px}.s299{min-width:28px;width:28px;min-height:16px}.f85{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:3px;padding-bottom:3px}.s300{width:28px;height:10px}.ps276{margin-left:7px;margin-top:1px}.ps277{margin-left:7px;margin-top:1px}.ps278{margin-left:7px;margin-top:1px}.s301{min-width:38px;width:38px;min-height:16px}.s302{width:38px;height:10px}.ps279{margin-left:8px}.s303{min-width:57px;width:57px;min-height:19px}.f86{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:5px;padding-bottom:4px}.s304{width:57px;height:10px}.ps280{margin-left:7px}.s305{min-width:60px;width:60px;min-height:19px}.s306{width:60px;height:10px}.ps281{margin-top:12px}.s307{min-width:320px;width:320px;min-height:41px}.s308{min-width:320px;width:320px;min-height:41px}.s309{min-width:320px;width:320px;min-height:41px}.ps282{margin-top:-35px}.s310{min-width:79px;width:79px;min-height:28px;height:28px}.s311{min-width:79px;width:79px;min-height:28px;height:28px}.v85{display:none}.s312{min-width:267px;width:267px;min-height:353px;height:353px}.s313{min-width:267px;width:267px;min-height:58px;height:58px}.s314{min-width:267px;width:267px;min-height:58px}.ps283{margin-top:14px}.s315{min-width:267px;width:267px;min-height:31px}.c272{height:31px}.f87{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps284{margin-left:0;margin-top:1px}.ps285{margin-top:14px}.v86{display:inline-block}.ps286{margin-left:0;margin-top:1px}.ps287{margin-top:14px}.s316{min-width:267px;width:267px}.ps288{margin-left:0;margin-top:1px}.ps289{margin-top:14px}.ps290{margin-top:61px}.s317{min-width:320px}.s318{min-width:320px;width:320px;min-height:3345px}.s319{min-width:320px;min-height:2032px}.ps292{margin-left:17px}.s320{min-width:285px;width:285px;min-height:69px}.c281{height:69px}.f89{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps293{margin-left:17px;margin-top:21px}.s321{min-width:285px;width:285px;min-height:174px;height:174px}.i18{width:285px;height:160px;top:7px}.ps294{margin-left:17px;margin-top:22px}.s322{min-width:292px;width:292px;min-height:1746px}.c283{height:1746px}.f90{font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.f91{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.ps295{margin-top:-2754px}.s323{min-width:320px;width:320px;min-height:13px}.ps297{margin-left:42px}.s324{min-width:236px;width:236px;min-height:13px}.s325{min-width:16px;width:16px;min-height:13px}.f92{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251;padding-top:4px;padding-bottom:4px}.s326{width:16px;height:5px}.ps298{margin-left:4px}body{--d:3;--s:320}}</style>
<script>!function(){var A=new Image;A.onload=A.onerror=function(){1!=A.height&&document.body.classList.remove("webp")},A.src="data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAD8D+JaQAA3AA/ua1AAA"}();
</script>
<link onload="this.media='all';this.onload=null;" rel="stylesheet" href="css/site.acf5e1.css" media="print">
</head>
<body class="webp" id="b">
<script>var p=document.createElement("P");p.innerHTML="&nbsp;",p.style.cssText="position:fixed;visible:hidden;font-size:100px;zoom:1",document.body.appendChild(p);var rsz=function(e){return function(){var r=Math.trunc(1e3/parseFloat(window.getComputedStyle(e).getPropertyValue("font-size")))/10,t=document.body;r!=t.style.getPropertyValue("--f")&&t.style.setProperty("--f",r)}}(p);if("ResizeObserver"in window){var ro=new ResizeObserver(rsz);ro.observe(p)}else if("requestAnimationFrame"in window){var raf=function(){rsz(),requestAnimationFrame(raf)};requestAnimationFrame(raf)}else setInterval(rsz,100);</script>

<script>!function(){var e=function(){var e=document.body,n=window.innerWidth,t=getComputedStyle(e).getPropertyValue("--s");n=320==t?Math.min(479,n):480==t?Math.min(610,n):t,e.style.setProperty("--z",Math.trunc(n/t*1000)/1000)};window.addEventListener?window.addEventListener("resize",e,!0):window.onscroll=e,e()}();</script>

<div class="ps269 v80 s294">
<div class="v81 ps270 s295 c259">
<p class="p35 f84"><a href="#">PrimalTalk101</a></p>
</div>
</div>
<div class="v82 ps271 s296 c260">
<div class="ps272">
</div>
<div class="ps273 v80 s297">
<div class="v81 ps274 s298 c261">
<div class="v81 ps275 s299 c262">
<a href="./" class="f85 btn71 v83 s300">Home</a>
</div>
<div class="v81 ps276 s299 c263">
<a href="about.html" class="f85 btn72 v83 s300">About</a>
</div>
<div class="v81 ps277 s299 c264">
<a href="media.html" class="f85 btn73 v83 s300">Media</a>
</div>
<div class="v81 ps278 s299 c265">
<a href="#" class="f85 btn74 v83 s300">Blog</a>
</div>
<div class="v81 ps277 s301 c266">
<a href="contact.html" class="f85 btn75 v83 s302">Contact</a>
</div>
<div class="v81 ps279 s303 c267">
<a href="special-offer.html" class="f86 btn76 v83 s304">Special Offer</a>
</div>
<div class="v81 ps280 s305 c268">
<a href="work-with-me.html" class="f86 btn77 v83 s306">Work with me!</a>
</div>
</div>
</div>
</div>
<div class="ps281 v80 s307">
<div class="v81 ps270 s308 c261">
<div class="v81 ps270 s308 w11">
<div class="v81 ps270 s309 c269"></div>
<div class="v84 ps282 s310 c270">
<ul class="menu-dropdown-1 v81 ps270 s311 m21" id="m2">
<li class="v11 ps39 s46 mit3">
<div class="menu-content mcv3">
<div class="v12 ps40 s47 c253">
<div class="v11 ps41 s48 c34">
<p class="p6 f15">Menu</p>
</div>
</div>
</div>
<ul class="menu-dropdown v85 ps270 s312 m22" id="m1">
<li class="v81 ps270 s313 mit15">
<a href="./" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c271"><div class="v81 ps283 s315 c272"><p class="p36 f87">Home</p></div></div></div></a>
</li>
<li class="v81 ps284 s313 mit15">
<a href="about.html" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c273"><div class="v81 ps285 s315 c272"><p class="p36 f87">About</p></div></div></div></a>
</li>
<li class="v86 ps286 s313 mit15">
<a href="media.html" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c274"><div class="v81 ps287 s316 c275"><p class="p36 f88">Media</p></div></div></div></a>
</li>
<li class="v86 ps288 s313 mit15">
<a href="contact.html" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c276"><div class="v81 ps289 s316 c275"><p class="p36 f88">Contact</p></div></div></div></a>
</li>
<li class="v86 ps286 s313 mit15">
<a href="special-offer.html" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c277"><div class="v81 ps287 s316 c275"><p class="p36 f88">Special Offer</p></div></div></div></a>
</li>
<li class="v86 ps288 s313 mit15">
<a href="work-with-me.html" class="ml11"><div class="menu-content mcv15"><div class="v81 ps270 s314 c278"><div class="v81 ps289 s316 c275"><p class="p36 f88">Work with me!</p></div></div></div></a>
</li>
</ul>
</li>
</ul>
</div>
</div>
</div>
</div>
<div class="v87 ps290 s317 c279">
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
<div class="ps295 v80 s323">
<div class="v81 ps297 s324 c284">
<div class="ps296">
<?php

    echo '<style>.pbdn{display:none}.pbc{border: 0;background-color:#c0c0c0;color:#fff;border-color:#677a85}</style>';
    $control = '<div class="v81 ps270 s325 c285 {btnclass}"><a href="#" class="f92 btn78 v83 s326 {lnkclass}">&lt;&lt;</a></div>';
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

    $control = '<div class="v81 ps298 s325 c286 {btnclass}"><a href="#" class="f92 btn79 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c287 {btnclass}"><a href="#" class="f92 btn80 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c288 {btnclass}"><a href="#" class="f92 btn81 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c289 {btnclass}"><a href="#" class="f92 btn82 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c290 {btnclass}"><a href="#" class="f92 btn83 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c291 {btnclass}"><a href="#" class="f92 btn84 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c292 {btnclass}"><a href="#" class="f92 btn85 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c293 {btnclass}"><a href="#" class="f92 btn86 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c294 {btnclass}"><a href="#" class="f92 btn87 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c295 {btnclass}"><a href="#" class="f92 btn88 v83 s326 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v81 ps298 s325 c296 {btnclass}"><a href="#" class="f92 btn89 v83 s326 {lnkclass}">&gt;&gt;</a></div>';
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
<div class="btf c258">
</div>
<script>dpth="/";!function(){for(var e=["js/jquery.4612ac.js","js/jqueryui.4612ac.js","js/menu.4612ac.js","js/menu-dropdown-animations.4612ac.js","js/menu-dropdown.acf5e1.js","js/menu-dropdown-1.acf5e1.js","js/shapes.4612ac.js","js/blog-index.acf5e1.js"],n={},s=-1,t=function(t){var o=new XMLHttpRequest;o.open("GET",e[t],!0),o.onload=function(){for(n[t]=o.responseText;s<8&&void 0!==n[s+1];){s++;var e=document.createElement("script");e.textContent=n[s],document.body.appendChild(e)}},o.send()},o=0;o<8;o++)t(o)}();
</script>
</body>
</html>