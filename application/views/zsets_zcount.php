用途：返回有序集key中，score值在min和max之间(默认包括score值等于min或max)的成员个数。  <br/><br/>

<form action="/home/zsets/zcount" method="post">
    key:
    <input type="text" name="key" />

    start:
    <input type="text" name="start" />

    stop:
    <input type="text" name="stop" />

    <select name="select_type">
        <option name="zCount" value="zCount">zCount</option>
    </select>

    <input type="submit" value="submit" />

</form>

</div>

</div>

</div>

</body>
</html>