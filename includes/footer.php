
<script src="js/popper.min.js"></script>
<script src="js/bootstrap4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <?php
   if(isset($_SESSION['status']))
   {?>
   <script>
      swal("<?php echo $_SESSION['status']; ?>", "-By Pigeon!");
   </script>

   <?php
      unset($_SESSION['status']);
   }
   ?>
<script src="js/script.js"></script>


      
</body>
</html>
