<div id="message" style="display:flex;margin-top: 10px;padding:10px; background-color:white ;">
    <div>
        <div style="font-weight: bold;color:#405d9b"><?php echo $USER[0]["username"]?></div>
        <?php echo $row['message']?>
        <?php var_dump($row['id']) ?>
        <a class="text-red-700 hover:bg-red-500 hover:text-white px-3 py-2 rounded-md text-la font-larg" href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
        <?php  ?>
        
    </div>
</div>