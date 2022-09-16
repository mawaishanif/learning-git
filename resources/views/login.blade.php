<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  / margin: 24px 0 12px 0; /
}

img.avatar {
  width: 20%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/ Change styles for span and cancel button on extra small screens /
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>
<form action="{{ url('/authenticate') }}" method="get">
  <div class="imgcontainer">
    <img src="https://seeklogo.net/wp-content/uploads/2017/02/shopify-logo.png" alt="Avatar" class="avatar">
  </div>

  <div class="container" style="margin-left: 25%;margin-right: 25%;text-align: center;">
    <label for="uname"><b>Shop URL (App Extension)</b></label>
    <input type="text" placeholder="store.myshopify.com" name="shop" required>

    <button type="submit">Login</button>

  </div>
</form>

</body>
</html>
