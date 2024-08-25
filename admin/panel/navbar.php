<?php
include('../../config/config.php');
include '../../helper/function.php';
?>
<aside>
    <ul>
        <li><a href="<?php echo route('admin/dashboard/dashboard.php') ?>">Dashboard</a></li>
        <li><a href="<?php echo route('admin/users/users-create.php') ?>">Admin/users</a></li>
        <li><a href="<?php echo route('admin/category/categories.php') ?>">Categories</a></li>
        <li><a href="<?php echo route('admin/blogs/blogs.php')?>">Blogs</a></li>
        <li><a href="<?php echo route('client/profile/profile.php')?>">Profile</a></li>
        <li><a href="<?php echo route('admin/panel/logout.php')?>">Logout</a></li>
        <li><a href=<?php echo route('auth/register.php') ?>>Register</a></li>
        <li><a href=<?php echo route('auth/login.php') ?>>Login</a></li>
        <li><a href=<?php echo route('admin/index/home.php') ?>>Home</a></li>
    </ul>
</aside>
