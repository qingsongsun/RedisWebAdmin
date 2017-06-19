<div class="row">

    <div class="col-md-2">


<form action="/home" method="post">
<select name="redis_server">


    <?php
    foreach($redis as $v){ ?>
        <option value="<?php echo $v[0] . ':' . $v[1];?>" <?php if ( strcmp($redis_server,$v[0] . ':' . $v[1]) == 0 ){echo "selected";} ?> > <?php echo $v[0].':'.$v[1]; ?> </option>;
    <?php } ?>

</select>

<br/>
<br/>

<input type="submit" value="操作该redis">

</form>

<br/>

<div id="jd">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id">-->
<b>系统信息</b>
    </div>

<div style="display: none;" id="dd">
<ul>
    <li><a href="/home/get/info" target="right">info</a></li>
</ul>
</div>

    <div id="jd1">
        <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
        <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id1">-->
        <b>Key相关</b>
    </div>

    <div style="display: none;" id="dd1">
        <ul>
            <li><a href="/home/key/key_type" target="right">key类型</a></li>
            <li><a href="/home/key/key_exists" target="right">key是否存在</a></li>
            <li><a href="/home/key/key_delete" target="right">删除Key</a></li>
            <li><a href="/home/key/key_ttl" target="right">TTL(秒)</a></li>
            <li><a href="/home/key/key_randomkey" target="right">随机返回一个key</a></li>
        </ul>
    </div>

<div id="jd2">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id2">-->
<b>字符串</b>
</div>

<div style="display: none;" id="dd2">
<ul>
    <li><a href="/home/string/get" target="right">Get</a></li>
    <li><a href="/home/string/getrange" target="right">getRange</a></li>
</ul>
</div>

<div id="jd3">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id3">-->
<b>哈希</b>
</div>

<div style="display: none;" id="dd3">
<ul>
    <li><a href="/home/hash/exists" target="right">hExists</a></li>
    <li><a href="/home/hash/hget" target="right">hGet</a></li>
    <li><a href="/home/hash/hgetall" target="right">hGetAll</a></li>
    <li><a href="/home/hash/hlen" target="right">hLen</a></li>
    <li><a href="/home/hash/hkeys" target="right">hKeys</a></li>
</ul>
</div>

<div id="jd4">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id4">-->
<b>列表</b>
</div>

<div style="display: none;" id="dd4">
<ul>
    <li><a href="/home/lists/lindex" target="right">lIndex</a></li>
    <li><a href="/home/lists/llen" target="right">lLen</a></li>
    <li><a href="/home/lists/lrange" target="right">lRange</a></li>
</ul>
</div>

<div id="jd5">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id5">-->
<b>集合</b>
</div>

<div style="display: none;" id="dd5">
<ul>
    <li><a href="/home/sets/scard" target="right">sCard</a></li>
    <li><a href="/home/sets/sdiff" target="right">sDiff</a></li>
    <li><a href="/home/sets/sinter" target="right">sInter</a></li>
    <li><a href="/home/sets/sismember" target="right">sIsMember</a></li>
    <li><a href="/home/sets/smembers" target="right">sMembers</a></li>
    <li><a href="/home/sets/srandmember" target="right">sRandMember</a></li>
    <li><a href="/home/sets/sunion" target="right">sUnion</a></li>
</ul>
</div>

<div id="jd6">
    <img alt="" src="<?php echo STATIC_IMAGES;?>folder.gif"></a>
    <!--<img alt="" src="<?php /*echo STATIC_IMAGES;*/?>blank.gif" id="id6">-->
<b>有序集合</b>
</div>

<div style="display: none;" id="dd6">
<ul>
    <li><a href="/home/zsets/zcard" target="right">zCard</a></li>
    <li><a href="/home/zsets/zcount" target="right">zCount</a></li>
</ul>
</div>

<img alt="" src="<?php echo STATIC_IMAGES;?>blank.gif"></a>
<img alt="" src="<?php echo STATIC_IMAGES;?>blank.gif">
<a href="/"> <b>返回首页</b> </a>

    </div>

    <div class="col-md-10">