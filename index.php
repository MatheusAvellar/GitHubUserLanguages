<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="en-us"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="author" content="Matheus Avellar"/>
    <title>User language usage</title>
    <link href="http://fonts.googleapis.com/css?family=Bree+Serif" type="text/css" rel="stylesheet"/>
    <link href="http://fonts.googleapis.com/css?family=PT+Sans:400,700" type="text/css" rel="stylesheet"/>
    <script src="http://avellar.ml/resources/jquery.js" type="text/javascript"></script>
    <link href="http://avellar.ml/resources/favicon.png" type="image/png" rel="shortcut icon"/>
    <link href="http://avellar.ml/resources/favicon.png" type="image/png" rel="icon"/>
    <style type="text/css">
        body {
            background-color: #1e1e1e;
            color: #fefefe;
            margin: 0;
        }
        div#main {
            font-family: "PT Sans", sans-serif;
        }
        div#app {
            padding: 30px;
            margin-left: 10vw;
            width: calc(80vw - 60px);
            border-left: 2px solid #b0b0b0;
            border-right: 2px solid #b0b0b0;
        }
        h1 {
            font-family: "Bree Serif", serif;
            text-align: center;
            font-size: 40px;
        }
        .entry {
            font-size: 20px;
        }
        span.lang:after {
            content: " - ";
            color: #b0b0b0;
        }
        div#langbar {
            width: 100%;
            height: 10px;
            margin-bottom: 20px;
        }
        .langpart {
            width: 10%;
            height: 100%;
            display: inline-block;
        }
        .JavaScript {  background-color: #f1e05a;  }
        .CSS {  background-color: #563d7c;  }
        .HTML {  background-color: #e44b23;  }
        .brainfuck {  background-color: #2F2530;  }
        .CS {  background-color: #178600;  }
        .PHP {  background-color: #4f5d95;  }
        .Python {  background-color: #3572a5;  }
        .Batchfile {  background-color: #cccccc;  }
        .Swift {  background-color: #ffac45;  }
        footer {
            bottom: 0px;
            position: fixed;
            width: 100vw;
            background-color: #fefefe;
            color: #1e1e1e;
            padding: 10px 20px;
        }
        li {
            list-style-type: none;
        }
        a.github {
            color: #3498db;
            text-decoration: none;
            border: 1px solid transparent;
            padding: 0px 3px;
        }
        a.github:hover {
            border: 1px solid #3498db;
            border-radius: 2px;
        }
        .nothing {
            font-size: 50px;
            font-family: "Bree Serif", serif;
            width: 100vw;
            position: absolute;
            padding-top: calc(50vh - 55px);
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="main">
        <header>
<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET["u"])) {
    function get_content_from_github($url) {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch,CURLOPT_USERAGENT, $_GET["u"]);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    echo "<h1>" . $_GET["u"] . "</h1>";

    try {
        $json = json_decode(get_content_from_github("https://api.github.com/users/" . $_GET["u"] . "/repos"), true);
    
        $langs = array();
        $c = 0;
    
        
        foreach ($json as $repo) {
            $c++;
            array_push($langs, json_decode(get_content_from_github($repo["languages_url"]), true));
        }
        
        $langsFiltered = array();
        
        $lc = 0;
        for ($i = 0; $i < count($langs); $i++) {
            foreach ($langs[$i] as $j => $p) {
                $langsFiltered[$j] += $p;
                $lc += $p;
            }
        }

        $langPercentage = array();
        foreach ($langsFiltered as $i => $j) {
            $langPercentage[$i] = (($j / $lc) * 100);
        }
        echo "<style type='text/css'>";
        foreach ($langPercentage as $i => $j) {
            echo "." . str_replace("#", "S", $i) . " {  width: " . $j . "%  }";
        }
        echo "</style>";

?>
        </header>
        <div id="app">
            <div id="langbar">
<?php
        
        foreach ($langsFiltered as $i => $j) {
            echo "<div class='langpart " . str_replace("#", "S", $i) . "'></div>";
        }
?>
            </div>
<?php
        foreach ($langsFiltered as $i => $j) {
            echo "<div class='entry'><span class='lang'>" . $i . "</span><span class='count'>" . $j . " bytes</span></div>";
        }
        echo "<br/>Out of " . $c . " repositories, and a total of " . $lc . " bytes of code.";
        
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "<div class='nothing'>No username given.</div>";
}

?>
        </div>
        <footer>
            <li>
                <a id="time">00:00:00</a>
                <script type="text/javascript">
                    (function() {
                        function _l () {
                            var d = new Date();
                            var t = {
                                h: d.getHours(),
                                m: d.getMinutes(),
                                s: d.getSeconds()
                            };
                            t.h = t.h < 10 ? "0" + t.h : t.h;
                            t.m = t.m < 10 ? "0" + t.m : t.m;
                            t.s = t.s < 10 ? "0" + t.s : t.s;
                            $("#time").text(t.h + ":" + t.m + ":" + t.s);
                            var _t = setTimeout(_l, 1000);
                        }
                        _l();
                    })();
                </script>
            </li>
            <li>
                Made with ❤ by <a class="github" href="http://github.com/MatheusAvellar" target="_blank">MatheusAvellar</a>
            </li>
        </footer>
    </div>
</body>
</html>