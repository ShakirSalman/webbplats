<?php
    session_start();

# Här förstör vi sessionen och skickar användaren till login sidan
    session_destroy();

    header('Location: index.html');
