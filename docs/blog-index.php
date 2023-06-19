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
#b{background-color:#fff}.ps313{position:relative;margin-top:15px}.v72{display:block;vertical-align:top}.s340{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:58px}.v73{display:inline-block;vertical-align:top}.ps314{position:relative;margin-left:0;margin-top:0}.s341{min-width:310px;width:310px;min-height:58px}.s342{min-width:310px;width:310px;min-height:58px}.c305{z-index:2;pointer-events:auto;overflow:hidden;height:58px}.p32{text-indent:0;padding-bottom:0;padding-right:0;text-align:left}.f93{font-family:"Helvetica Neue", sans-serif;font-size:47px;font-size:calc(47px * var(--f));line-height:1.107;font-weight:300;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#000;background-color:initial;text-shadow:none}.v74{display:none;vertical-align:top;overflow:visible}.v75{display:none;vertical-align:top}.s344{min-width:237px;width:237px;min-height:84px;height:84px}.m11{padding:0px 0px 0px 0px}.mcv11{display:inline-block}.s345{min-width:237px;width:237px;min-height:84px}.c307{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-image:url(images/hamburger-gray-84-1.png);background-color:transparent;background-repeat:no-repeat;background-position:50% 50%;background-size:contain}.webp .c307{background-image:url(images/hamburger-gray-84-1.webp)}.ps316{position:relative;margin-left:0;margin-top:32px}.s346{min-width:237px;width:237px;min-height:19px}.c308{pointer-events:auto;overflow:hidden;height:19px}.p33{text-indent:0;padding-bottom:0;padding-right:0;text-align:center}.f94{font-family:Lato;font-size:12px;font-size:calc(12px * var(--f));line-height:1.334;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:transparent;background-color:initial;text-shadow:none}.v76{display:none;vertical-align:top}.s347{min-width:182px;width:182px;min-height:528px;height:528px}.ml11{outline:0}.s348{min-width:182px;width:182px;min-height:174px;height:174px}.s349{min-width:182px;width:182px;min-height:174px}.c309{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps317{position:relative;margin-left:0;margin-top:43px}.s350{min-width:182px;width:182px;min-height:88px}.c310{pointer-events:auto;overflow:hidden;height:88px}.f95{font-family:Lato;font-size:60px;font-size:calc(60px * var(--f));line-height:1.318;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps318{position:relative;margin-left:0;margin-top:3px}.c311{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps319{position:relative;margin-left:0;margin-top:43px}.ps320{position:relative;margin-left:0;margin-top:96px}.c312{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps321{position:relative;margin-left:0;margin-top:71px}.s351{min-width:182px;width:182px;min-height:31px}.c313{pointer-events:auto;overflow:hidden;height:31px}.f96{font-family:Lato;font-size:20px;font-size:calc(20px * var(--f));line-height:1.351;font-weight:900;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#c0c0c0;background-color:initial;text-shadow:none}.ps322{position:relative;margin-left:0;margin-top:96px}.c314{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps323{position:relative;margin-left:0;margin-top:71px}.ps324{position:relative;margin-left:0;margin-top:3px}.c315{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.s352{min-width:408px;width:408px;min-height:270px;height:270px}.s353{min-width:408px;width:408px;min-height:88px;height:88px}.s354{min-width:408px;width:408px;min-height:88px}.c316{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.c317{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.c318{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps325{position:relative;margin-left:0;margin-top:96px}.c319{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps326{position:relative;margin-left:0;margin-top:96px}.c320{pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.v77{display:block;vertical-align:top}.ps327{position:relative;margin-top:0}.s355{width:100%;min-width:960px;min-height:76px}.c321{z-index:3;pointer-events:none;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps328{display:inline-block;width:0;height:0}.ps329{position:relative;margin-top:20px}.s356{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:56px}.ps330{position:relative;margin-left:6px;margin-top:0}.s357{min-width:929px;width:929px;min-height:56px}.ps331{position:relative;margin-left:0;margin-top:4px}.s358{min-width:83px;width:83px;min-height:49px}.c322{z-index:4;pointer-events:auto}.f97{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:10px;padding-bottom:9px;margin-top:0;margin-bottom:0}.btn71{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn71:hover{background-color:#82939e;border-color:#000;color:#000}.btn71:active{background-color:#52646f;border-color:#000;color:#fff}.v78{display:inline-block;overflow:hidden;outline:0}.s359{width:83px;padding-right:0;height:30px}.ps332{position:relative;margin-left:22px;margin-top:4px}.c323{z-index:5;pointer-events:auto}.btn72{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn72:hover{background-color:#82939e;border-color:#000;color:#000}.btn72:active{background-color:#52646f;border-color:#000;color:#fff}.ps333{position:relative;margin-left:22px;margin-top:4px}.c324{z-index:6;pointer-events:auto}.btn73{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn73:hover{background-color:#82939e;border-color:#000;color:#000}.btn73:active{background-color:#52646f;border-color:#000;color:#fff}.ps334{position:relative;margin-left:23px;margin-top:4px}.c325{z-index:7;pointer-events:auto}.btn74{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn74:hover{background-color:#82939e;border-color:#000;color:#000}.btn74:active{background-color:#52646f;border-color:#000;color:#fff}.s360{min-width:114px;width:114px;min-height:49px}.c326{z-index:8;pointer-events:auto}.btn75{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn75:hover{background-color:#82939e;border-color:#000;color:#000}.btn75:active{background-color:#52646f;border-color:#000;color:#fff}.s361{width:114px;padding-right:0;height:30px}.ps335{position:relative;margin-left:22px;margin-top:0}.s362{min-width:170px;width:170px;min-height:56px}.c327{z-index:9;pointer-events:auto}.f98{font-family:"Helvetica Neue", sans-serif;font-size:25px;font-size:calc(25px * var(--f));line-height:1.201;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn76{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn76:hover{background-color:#82939e;border-color:#000;color:#000}.btn76:active{background-color:#52646f;border-color:#000;color:#fff}.s363{width:170px;padding-right:0;height:30px}.ps336{position:relative;margin-left:22px;margin-top:0}.s364{min-width:180px;width:180px;min-height:56px}.c328{z-index:10;pointer-events:auto}.btn77{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d;color:#fff}.btn77:hover{background-color:#82939e;border-color:#000;color:#000}.btn77:active{background-color:#52646f;border-color:#000;color:#fff}.s365{width:180px;padding-right:0;height:30px}.v79{display:none;vertical-align:top}.v80{display:inline-block;vertical-align:top;overflow:hidden}.ps339{position:relative;margin-top:109px}.s368{width:100%;min-width:960px}.c330{z-index:11;pointer-events:none}.ps340{position:relative;margin-top:0}.s369{min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:2125px}.s370{width:100%;min-width:960px;min-height:1427px}.c331{z-index:12}.ps341{position:relative;margin-left:120px;margin-top:0}.s371{min-width:720px;width:720px;min-height:94px}.c332{z-index:13;pointer-events:auto;overflow:hidden;height:94px}.f99{font-family:Lato;font-size:36px;font-size:calc(36px * var(--f));line-height:1.307;font-weight:300;font-style:normal;text-decoration:underline;text-transform:none;letter-spacing:normal;color:#54387d;background-color:initial;text-shadow:none}.ps342{position:relative;margin-left:143px;margin-top:51px}.s372{min-width:730px;width:730px;min-height:410px;height:410px}.c333{z-index:14;pointer-events:auto}.i20{position:absolute;left:0;width:730px;height:410px;top:0;border:0}.i21{width:100%;height:100%;display:inline-block;-webkit-transform:translate3d(0,0,0)}.ps343{position:relative;margin-left:120px;margin-top:33px}.s373{min-width:753px;width:753px;min-height:839px}.c334{z-index:15;pointer-events:auto;overflow:hidden;height:839px}.f100{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.f101{font-family:Lato;font-size:15px;font-size:calc(15px * var(--f));line-height:1.668;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;color:#404040;background-color:initial;text-shadow:none}.ps344{position:relative;margin-top:100px}.s374{pointer-events:none;min-width:960px;width:960px;margin-left:auto;margin-right:auto;min-height:40px}.ps345{display:inline-block;position:relative;left:50%;-ms-transform:translate(-50%,0);-webkit-transform:translate(-50%,0);transform:translate(-50%,0)}.ps346{position:relative;margin-left:126px;margin-top:0}.s375{min-width:708px;width:708px;min-height:40px}.c335{z-index:20}.s376{min-width:48px;width:48px;min-height:40px}.c336{z-index:21;pointer-events:auto}.f102{font-family:"Helvetica Neue", sans-serif;font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;font-weight:400;font-style:normal;text-decoration:none;text-transform:none;letter-spacing:normal;text-shadow:none;text-indent:0;text-align:center;padding-top:13px;padding-bottom:13px;margin-top:0;margin-bottom:0}.btn78{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn78:hover{background-color:#82939e;border-color:#000;color:#000}.btn78:active{background-color:#52646f;border-color:#000;color:#fff}.s377{width:48px;padding-right:0;height:14px}.ps347{position:relative;margin-left:12px;margin-top:0}.c337{z-index:22;pointer-events:auto}.btn79{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn79:hover{background-color:#82939e;border-color:#000;color:#000}.btn79:active{background-color:#52646f;border-color:#000;color:#fff}.c338{z-index:23;pointer-events:auto}.btn80{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn80:hover{background-color:#82939e;border-color:#000;color:#000}.btn80:active{background-color:#52646f;border-color:#000;color:#fff}.c339{z-index:24;pointer-events:auto}.btn81{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn81:hover{background-color:#82939e;border-color:#000;color:#000}.btn81:active{background-color:#52646f;border-color:#000;color:#fff}.c340{z-index:25;pointer-events:auto}.btn82{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn82:hover{background-color:#82939e;border-color:#000;color:#000}.btn82:active{background-color:#52646f;border-color:#000;color:#fff}.c341{z-index:26;pointer-events:auto}.btn83{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn83:hover{background-color:#82939e;border-color:#000;color:#000}.btn83:active{background-color:#52646f;border-color:#000;color:#fff}.c342{z-index:27;pointer-events:auto}.btn84{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn84:hover{background-color:#82939e;border-color:#000;color:#000}.btn84:active{background-color:#52646f;border-color:#000;color:#fff}.c343{z-index:28;pointer-events:auto}.btn85{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn85:hover{background-color:#82939e;border-color:#000;color:#000}.btn85:active{background-color:#52646f;border-color:#000;color:#fff}.c344{z-index:29;pointer-events:auto}.btn86{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn86:hover{background-color:#82939e;border-color:#000;color:#000}.btn86:active{background-color:#52646f;border-color:#000;color:#fff}.c345{z-index:30;pointer-events:auto}.btn87{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn87:hover{background-color:#82939e;border-color:#000;color:#000}.btn87:active{background-color:#52646f;border-color:#000;color:#fff}.c346{z-index:31;pointer-events:auto}.btn88{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn88:hover{background-color:#82939e;border-color:#000;color:#000}.btn88:active{background-color:#52646f;border-color:#000;color:#fff}.c347{z-index:32;pointer-events:auto}.btn89{border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#b8c8d2;color:#000}.btn89:hover{background-color:#82939e;border-color:#000;color:#000}.btn89:active{background-color:#52646f;border-color:#000;color:#fff}body{--d:0;--s:960}@media (min-width:768px) and (max-width:959px) {.ps313{margin-top:12px}.s340{min-width:768px;width:768px;min-height:46px}.s341{min-width:248px;width:248px;min-height:46px}.s342{min-width:248px;width:248px;min-height:46px}.c305{height:46px}.f93{font-size:37px;font-size:calc(37px * var(--f));line-height:1.109}.s344{min-width:190px;width:190px;min-height:67px;height:67px}.s345{min-width:190px;width:190px;min-height:67px}.c307{background-image:url(images/hamburger-gray-67.png)}.webp .c307{background-image:url(images/hamburger-gray-67.webp)}.ps316{margin-top:26px}.s346{min-width:190px;width:190px;min-height:15px}.c308{height:15px}.f94{font-size:9px;font-size:calc(9px * var(--f))}.s347{min-width:146px;width:146px;min-height:422px;height:422px}.s348{min-width:146px;width:146px;min-height:139px;height:139px}.s349{min-width:146px;width:146px;min-height:139px}.ps317{margin-top:34px}.s350{min-width:146px;width:146px;min-height:71px}.c310{height:71px}.f95{font-size:48px;font-size:calc(48px * var(--f));line-height:1.313}.ps318{margin-top:2px}.ps319{margin-top:34px}.ps320{margin-top:76px}.ps321{margin-top:54px}.s351{min-width:146px;width:146px}.ps322{margin-top:76px}.ps323{margin-top:54px}.s352{min-width:326px;width:326px;min-height:218px;height:218px}.s353{min-width:326px;width:326px;min-height:71px;height:71px}.s354{min-width:326px;width:326px;min-height:71px}.ps325{margin-top:77px}.ps326{margin-top:77px}.s355{min-width:768px;min-height:61px}.ps329{margin-top:16px}.s356{min-width:768px;width:768px;min-height:45px}.ps330{margin-left:5px}.s357{min-width:743px;width:743px;min-height:45px}.s358{min-width:66px;width:66px;min-height:39px}.f97{font-size:20px;font-size:calc(20px * var(--f));padding-top:8px;padding-bottom:7px}.s359{width:66px;height:24px}.ps332{margin-left:18px}.ps333{margin-left:18px}.ps334{margin-left:19px}.s360{min-width:91px;width:91px;min-height:39px}.s361{width:91px;height:24px}.ps335{margin-left:17px}.s362{min-width:136px;width:136px;min-height:45px}.f98{font-size:20px;font-size:calc(20px * var(--f));padding-top:11px;padding-bottom:10px}.s363{width:136px;height:24px}.ps336{margin-left:18px}.s364{min-width:144px;width:144px;min-height:45px}.s365{width:144px;height:24px}.ps339{margin-top:87px}.s368{min-width:768px}.s369{min-width:768px;width:768px;min-height:1701px}.s370{min-width:768px;min-height:1142px}.ps341{margin-left:96px}.s371{min-width:576px;width:576px;min-height:75px}.c332{height:75px}.f99{font-size:28px;font-size:calc(28px * var(--f));line-height:1.322}.ps342{margin-left:114px;margin-top:41px}.s372{min-width:584px;width:584px;min-height:328px;height:328px}.i20{width:584px;height:328px}.ps343{margin-left:96px;margin-top:27px}.s373{min-width:602px;width:602px;min-height:671px}.c334{height:671px}.f100{font-size:12px;font-size:calc(12px * var(--f));line-height:1.751}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:12px;font-size:calc(12px * var(--f))}.ps344{margin-top:80px}.s374{min-width:768px;width:768px;min-height:32px}.ps346{margin-left:101px}.s375{min-width:566px;width:566px;min-height:32px}.s376{min-width:38px;width:38px;min-height:32px}.f102{font-size:9px;font-size:calc(9px * var(--f));line-height:1.223;padding-top:11px;padding-bottom:10px}.s377{width:38px;height:11px}.ps347{margin-left:10px}body{--d:1;--s:768}}@media (min-width:480px) and (max-width:767px) {.ps313{margin-top:8px}.s340{min-width:480px;width:480px;min-height:105px}.s341{min-width:270px;width:270px;min-height:105px}.s342{min-width:270px;width:270px;min-height:56px}.c305{height:56px}.f93{font-size:40px;font-size:calc(40px * var(--f));line-height:1.126}.v74{display:inline-block}.ps315{position:relative;margin-left:5px;margin-top:7px}.s343{min-width:119px;width:119px;min-height:42px;height:42px}.c306{z-index:33;pointer-events:auto}.v75{display:inline-block}.s344{min-width:119px;width:119px;min-height:42px;height:42px}.s345{min-width:119px;width:119px;min-height:42px}.c307{background-image:url(images/hamburger-gray-42.png)}.webp .c307{background-image:url(images/hamburger-gray-42.webp)}.ps316{margin-top:16px}.s346{min-width:119px;width:119px;min-height:10px}.c308{height:10px}.f94{font-size:6px;font-size:calc(6px * var(--f))}.s347{min-width:401px;width:401px;min-height:618px;height:618px}.s348{min-width:401px;width:401px;min-height:87px;height:87px}.s349{min-width:401px;width:401px;min-height:87px}.ps317{margin-top:21px}.s350{min-width:401px;width:401px;min-height:46px}.c310{height:46px}.f95{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps318{margin-top:2px}.ps319{margin-top:20px}.ps320{margin-top:1px}.ps321{margin-top:21px}.s351{min-width:401px;width:401px;min-height:46px}.c313{height:46px}.f96{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps322{margin-top:2px}.ps323{margin-top:20px}.ps324{margin-top:1px}.s352{min-width:204px;width:204px;min-height:141px;height:141px}.s353{min-width:204px;width:204px;min-height:46px;height:46px}.s354{min-width:204px;width:204px;min-height:46px}.ps325{margin-top:2px}.ps326{margin-top:1px}.v77{display:none}.ps329{margin-top:0}.s356{min-width:480px;width:480px;min-height:29px}.ps330{margin-left:3px}.s357{min-width:465px;width:465px;min-height:29px}.ps331{margin-top:2px}.s358{min-width:42px;width:42px;min-height:24px}.f97{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:5px;padding-bottom:5px}.s359{width:42px;height:14px}.ps332{margin-left:11px;margin-top:2px}.ps333{margin-left:10px;margin-top:2px}.ps334{margin-left:11px;margin-top:2px}.s360{min-width:57px;width:57px;min-height:24px}.s361{width:57px;height:14px}.ps335{margin-left:12px}.s362{min-width:86px;width:86px;min-height:29px}.f98{font-size:12px;font-size:calc(12px * var(--f));line-height:1.168;padding-top:8px;padding-bottom:7px}.s363{width:86px;height:14px}.ps336{margin-left:10px}.s364{min-width:90px;width:90px;min-height:29px}.s365{width:90px;height:14px}.ps337{position:relative;margin-top:-50px}.v79{display:block}.s366{pointer-events:none;min-width:480px;width:480px;margin-left:auto;margin-right:auto;min-height:56px}.ps338{position:relative;margin-left:0;margin-top:0}.s367{min-width:480px;width:480px;min-height:56px}.c329{z-index:1;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps339{margin-top:115px}.s368{min-width:480px}.s369{min-width:480px;width:480px;min-height:5017px}.s370{min-width:480px;min-height:3048px}.ps341{margin-left:26px}.s371{min-width:428px;width:428px;min-height:104px}.c332{height:104px}.f99{font-size:30px;font-size:calc(30px * var(--f));line-height:1.334}.ps342{margin-left:26px;margin-top:31px}.s372{min-width:428px;width:428px;min-height:261px;height:261px}.i20{width:428px;height:240px;top:11px}.ps343{margin-left:26px}.s373{min-width:438px;width:438px;min-height:2619px}.c334{height:2619px}.f100{font-size:24px;font-size:calc(24px * var(--f))}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:24px;font-size:calc(24px * var(--f));line-height:1.626}.ps344{margin-top:-4132px}.s374{min-width:480px;width:480px;min-height:20px}.ps346{margin-left:63px}.s375{min-width:354px;width:354px;min-height:20px}.s376{min-width:24px;width:24px;min-height:20px}.f102{font-size:6px;font-size:calc(6px * var(--f));padding-top:7px;padding-bottom:6px}.s377{width:24px;height:7px}.ps347{margin-left:6px}body{--d:2;--s:480}}@media (max-width:479px) {.ps313{margin-top:5px}.s340{min-width:320px;width:320px;min-height:70px}.s341{min-width:180px;width:180px;min-height:70px}.s342{min-width:180px;width:180px;min-height:37px}.c305{height:37px}.f93{font-size:27px;font-size:calc(27px * var(--f));line-height:1.112}.v74{display:inline-block}.ps315{position:relative;margin-left:3px;margin-top:5px}.s343{min-width:79px;width:79px;min-height:28px;height:28px}.c306{z-index:33;pointer-events:auto}.v75{display:inline-block}.s344{min-width:79px;width:79px;min-height:28px;height:28px}.s345{min-width:79px;width:79px;min-height:28px}.c307{background-image:url(images/hamburger-gray-28.png)}.webp .c307{background-image:url(images/hamburger-gray-28.webp)}.ps316{margin-top:10px}.s346{min-width:79px;width:79px;min-height:7px}.c308{height:7px}.f94{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251}.s347{min-width:267px;width:267px;min-height:412px;height:412px}.s348{min-width:267px;width:267px;min-height:58px;height:58px}.s349{min-width:267px;width:267px;min-height:58px}.ps317{margin-top:14px}.s350{min-width:267px;width:267px;min-height:31px}.c310{height:31px}.f95{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps318{margin-top:1px}.ps319{margin-top:14px}.ps320{margin-top:1px}.ps321{margin-top:14px}.s351{min-width:267px;width:267px}.ps322{margin-top:1px}.ps323{margin-top:14px}.ps324{margin-top:1px}.s352{min-width:136px;width:136px;min-height:95px;height:95px}.s353{min-width:136px;width:136px;min-height:31px;height:31px}.s354{min-width:136px;width:136px;min-height:31px}.ps325{margin-top:1px}.ps326{margin-top:1px}.v77{display:none}.ps329{margin-top:0}.s356{min-width:320px;width:320px;min-height:19px}.ps330{margin-left:2px}.s357{min-width:310px;width:310px;min-height:19px}.ps331{margin-top:1px}.s358{min-width:28px;width:28px;min-height:16px}.f97{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:3px;padding-bottom:3px}.s359{width:28px;height:10px}.ps332{margin-left:7px;margin-top:1px}.ps333{margin-left:7px;margin-top:1px}.ps334{margin-left:7px;margin-top:1px}.s360{min-width:38px;width:38px;min-height:16px}.s361{width:38px;height:10px}.ps335{margin-left:8px}.s362{min-width:57px;width:57px;min-height:19px}.f98{font-size:8px;font-size:calc(8px * var(--f));line-height:1.251;padding-top:5px;padding-bottom:4px}.s363{width:57px;height:10px}.ps336{margin-left:7px}.s364{min-width:60px;width:60px;min-height:19px}.s365{width:60px;height:10px}.ps337{position:relative;margin-top:-33px}.v79{display:block}.s366{pointer-events:none;min-width:320px;width:320px;margin-left:auto;margin-right:auto;min-height:37px}.ps338{position:relative;margin-left:0;margin-top:0}.s367{min-width:320px;width:320px;min-height:37px}.c329{z-index:1;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;background-color:#54387d}.ps339{margin-top:77px}.s368{min-width:320px}.s369{min-width:320px;width:320px;min-height:3345px}.s370{min-width:320px;min-height:2032px}.ps341{margin-left:17px}.s371{min-width:285px;width:285px;min-height:69px}.c332{height:69px}.f99{font-size:20px;font-size:calc(20px * var(--f));line-height:1.351}.ps342{margin-left:17px;margin-top:21px}.s372{min-width:285px;width:285px;min-height:174px;height:174px}.i20{width:285px;height:160px;top:7px}.ps343{margin-left:17px;margin-top:22px}.s373{min-width:292px;width:292px;min-height:1746px}.c334{height:1746px}.f100{font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.f101{font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;font-size:16px;font-size:calc(16px * var(--f));line-height:1.688}.ps344{margin-top:-2754px}.s374{min-width:320px;width:320px;min-height:13px}.ps346{margin-left:42px}.s375{min-width:236px;width:236px;min-height:13px}.s376{min-width:16px;width:16px;min-height:13px}.f102{font-size:4px;font-size:calc(4px * var(--f));line-height:1.251;padding-top:4px;padding-bottom:4px}.s377{width:16px;height:5px}.ps347{margin-left:4px}body{--d:3;--s:320}}@media (-webkit-min-device-pixel-ratio:1.7), (min-resolution:144dpi) {.c307{background-image:url(images/hamburger-gray-168.png);background-size:contain}.webp .c307{background-image:url(images/hamburger-gray-168.webp)}}@media (min-width:768px) and (max-width:959px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:768px) and (max-width:959px) and (min-resolution:144dpi) {.c307{background-image:url(images/hamburger-gray-134.png);background-size:contain}.webp .c307{background-image:url(images/hamburger-gray-134.webp)}}@media (min-width:480px) and (max-width:767px) and (-webkit-min-device-pixel-ratio:1.7), (min-width:480px) and (max-width:767px) and (min-resolution:144dpi) {.c307{background-image:url(images/hamburger-gray-84.png);background-size:contain}.webp .c307{background-image:url(images/hamburger-gray-84.webp)}}@media (max-width:479px) and (-webkit-min-device-pixel-ratio:1.7), (max-width:479px) and (min-resolution:144dpi) {.c307{background-image:url(images/hamburger-gray-56.png);background-size:contain}.webp .c307{background-image:url(images/hamburger-gray-56.webp)}}</style>
<script>!function(){var A=new Image;A.onload=A.onerror=function(){1!=A.height&&document.body.classList.remove("webp")},A.src="data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAD8D+JaQAA3AA/ua1AAA"}();
</script>
<link onload="this.media='all';this.onload=null;" rel="stylesheet" href="css/site.ef433b.css" media="print">
</head>
<body class="webp" id="b">
<script>var p=document.createElement("P");p.innerHTML="&nbsp;",p.style.cssText="position:fixed;visible:hidden;font-size:100px;zoom:1",document.body.appendChild(p);var rsz=function(e){return function(){var r=Math.trunc(1e3/parseFloat(window.getComputedStyle(e).getPropertyValue("font-size")))/10,t=document.body;r!=t.style.getPropertyValue("--f")&&t.style.setProperty("--f",r)}}(p);if("ResizeObserver"in window){var ro=new ResizeObserver(rsz);ro.observe(p)}else if("requestAnimationFrame"in window){var raf=function(){rsz(),requestAnimationFrame(raf)};requestAnimationFrame(raf)}else setInterval(rsz,100);</script>

<script>!function(){var e=function(){var e=document.body,n=window.innerWidth,t=getComputedStyle(e).getPropertyValue("--s");n=320==t?Math.min(479,n):480==t?Math.min(610,n):t,e.style.setProperty("--z",Math.trunc(n/t*1000)/1000)};window.addEventListener?window.addEventListener("resize",e,!0):window.onscroll=e,e()}();</script>

<div class="ps313 v72 s340">
<div class="v73 ps314 s341 c304">
<div class="v73 ps314 s342 c305">
<p class="p32 f93"><a href="#">PrimalTalk101</a></p>
</div>
<div class="v74 ps315 s343 c306">
<ul class="menu-dropdown-1 v75 ps314 s344 m11" id="m1">
<li class="v73 ps314 s344 mit11">
<div class="menu-content mcv11">
<div class="v73 ps314 s345 c307">
<div class="v73 ps316 s346 c308">
<p class="p33 f94">Menu</p>
</div>
</div>
</div>
<ul class="menu-dropdown v76 ps314 s347 m11">
<li class="v73 ps314 s348 mit11">
<a href="./" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c309"><div class="v73 ps317 s350 c310"><p class="p32 f95">Home</p></div></div></div></a>
</li>
<li class="v73 ps318 s348 mit11">
<a href="about.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c311"><div class="v73 ps319 s350 c310"><p class="p32 f95">About</p></div></div></div></a>
</li>
<li class="v75 ps320 s348 mit11">
<a href="media.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c312"><div class="v73 ps321 s351 c313"><p class="p32 f96">Media</p></div></div></div></a>
</li>
<li class="v75 ps322 s348 mit11">
<a href="contact.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c314"><div class="v73 ps323 s351 c313"><p class="p32 f96">Contact</p></div></div></div></a>
</li>
<li data-anim="expand_down:0:270;expand_down:0:218;expand_down:0:141;expand_down:0:95" class="v73 ps324 s348 mit11">
<div class="menu-content mcv11">
<div class="v73 ps314 s349 c315">
<div class="v73 ps317 s350 c310">
<p class="p32 f95">BLOG</p>
</div>
</div>
</div>
<ul class="menu-dropdown v76 ps314 s352 m11">
<li class="v73 ps314 s353 mit11">
<a href="#" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s354 c316"><div class="v73 ps314 s354 c310"><p class="p32 f95">BLOG &mdash; Index</p></div></div></div></a>
</li>
<li class="v73 ps318 s353 mit11">
<a href="blog-post-1.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s354 c317"><div class="v73 ps314 s354 c310"><p class="p32 f95">Blog Post 1</p></div></div></div></a>
</li>
<li class="v73 ps324 s353 mit11">
<a href="blog-post.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s354 c318"><div class="v73 ps314 s354 c310"><p class="p32 f95">Blog Post</p></div></div></div></a>
</li>
</ul>
</li>
<li class="v75 ps325 s348 mit11">
<a href="special-offer.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c319"><div class="v73 ps323 s351 c313"><p class="p32 f96">Special Offer</p></div></div></div></a>
</li>
<li class="v75 ps326 s348 mit11">
<a href="work-with-me.html" class="ml11"><div class="menu-content mcv11"><div class="v73 ps314 s349 c320"><div class="v73 ps321 s351 c313"><p class="p32 f96">Work with me!</p></div></div></div></a>
</li>
</ul>
</li>
</ul>
</div>
</div>
</div>
<div class="v77 ps327 s355 c321">
<div class="ps328">
</div>
<div class="ps329 v72 s356">
<div class="v73 ps330 s357 c304">
<div class="v73 ps331 s358 c322">
<a href="./" class="f97 btn71 v78 s359">Home</a>
</div>
<div class="v73 ps332 s358 c323">
<a href="about.html" class="f97 btn72 v78 s359">About</a>
</div>
<div class="v73 ps333 s358 c324">
<a href="media.html" class="f97 btn73 v78 s359">Media</a>
</div>
<div class="v73 ps334 s358 c325">
<a href="#" class="f97 btn74 v78 s359">Blog</a>
</div>
<div class="v73 ps333 s360 c326">
<a href="contact.html" class="f97 btn75 v78 s361">Contact</a>
</div>
<div class="v73 ps335 s362 c327">
<a href="special-offer.html" class="f98 btn76 v78 s363">Special Offer</a>
</div>
<div class="v73 ps336 s364 c328">
<a href="work-with-me.html" class="f98 btn77 v78 s365">Work with me!</a>
</div>
</div>
</div>
</div>
<div class="ps337 v79 s366">
<div class="v75 ps338 s367 c329"></div>
</div>
<div class="v80 ps339 s368 c330">
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
<div class="ps344 v72 s374">
<div class="v73 ps346 s375 c335">
<div class="ps345">
<?php

    echo '<style>.pbdn{display:none}.pbc{border: 0;background-color:#c0c0c0;color:#fff;border-color:#677a85}</style>';
    $control = '<div class="v73 ps314 s376 c336 {btnclass}"><a href="#" class="f102 btn78 v78 s377 {lnkclass}">&lt;&lt;</a></div>';
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

    $control = '<div class="v73 ps347 s376 c337 {btnclass}"><a href="#" class="f102 btn79 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c338 {btnclass}"><a href="#" class="f102 btn80 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c339 {btnclass}"><a href="#" class="f102 btn81 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c340 {btnclass}"><a href="#" class="f102 btn82 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c341 {btnclass}"><a href="#" class="f102 btn83 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c342 {btnclass}"><a href="#" class="f102 btn84 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c343 {btnclass}"><a href="#" class="f102 btn85 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c344 {btnclass}"><a href="#" class="f102 btn86 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c345 {btnclass}"><a href="#" class="f102 btn87 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c346 {btnclass}"><a href="#" class="f102 btn88 v78 s377 {lnkclass}">{page_num}</a></div>';
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

    $control = '<div class="v73 ps347 s376 c347 {btnclass}"><a href="#" class="f102 btn89 v78 s377 {lnkclass}">&gt;&gt;</a></div>';
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
<div class="btf c303">
</div>
<script>dpth="/";!function(){for(var e=["js/jquery.4612ac.js","js/jqueryui.4612ac.js","js/menu.4612ac.js","js/menu-dropdown-animations.4612ac.js","js/menu-dropdown.ef433b.js","js/menu-dropdown-1.ef433b.js","js/shapes.4612ac.js","js/blog-index.ef433b.js"],n={},s=-1,t=function(t){var o=new XMLHttpRequest;o.open("GET",e[t],!0),o.onload=function(){for(n[t]=o.responseText;s<8&&void 0!==n[s+1];){s++;var e=document.createElement("script");e.textContent=n[s],document.body.appendChild(e)}},o.send()},o=0;o<8;o++)t(o)}();
</script>
</body>
</html>