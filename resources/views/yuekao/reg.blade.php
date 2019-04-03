<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="/yuekao" method="post" enctype="multipart/form-data">
    姓名：<input type="text" name="name" required><br>
    身份证号：<input type="number" name="number" required><br>
    证件照片：<input type="file" name="file" required><br>
    接口用途：<input type="text" name="yongtu" required><br>
    提交 <input type="submit" value="提交">
</form>
</body>
</html>