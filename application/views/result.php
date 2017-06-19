<?php if ($key !== null and $key !== false){ echo "<b>key: </b></br>" . $key; } ?>

</br></br>
<b>result: </b></br>
<?php if (isset($key)){
    if (is_array($result)){
        foreach ($result as $k=>$v){
            echo $k . ' => ' . $v;
            echo '<br />';
            echo '<br />';
        }
    }else{
        print_r($result);
    }
} ?>

</div>

</div>

</div>

</body>
</html>