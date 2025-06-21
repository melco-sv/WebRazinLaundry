<form action="proses.php" method="post" autocomplete="off">
  <div>
    <label for="">Nama</label>
    <input type="text" name="nama">
  </div>
  <div>
    <label for="">Email</label>
    <input type="email" name="email">
  </div>
<div>
    <label for="">No HP</label>
    <input type="tel" name="nohp" pattern="[0-9]*" inputmode="numeric" 
           oninput="this.value = this.value.replace(/[^0-9]/g, '');">
  </div>
  <div>
    <label for="">Password</label>
    <input type="password" name="pw" autocomplete="new-password">
  </div>
  <button type="submit" name="register">Register</button>
  <p>Sudah Memiliki akun? <a href="login.php">Login!</a></p>
</form>

