<?php 
    require_once "inc/functions.php";
    $action = $_GET['action'] ?? 'report';
    $error = $_GET['error'] ?? '0';
    $info = '';

    if( 'seed' == $action ){
        seed();
        $info = 'Seeding complete';
    }

    $fname = '';
    $lname = '';
    $roll = '';
    if( isset( $_POST['submit'] ) ){
        $fname = filter_input( INPUT_POST, 'fname', FILTER_UNSAFE_RAW );
        $lname = filter_input( INPUT_POST, 'lname', FILTER_UNSAFE_RAW );
        $roll  = filter_input( INPUT_POST, 'roll', FILTER_UNSAFE_RAW );
        $id    = filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW );
        if($id){
            if( $fname != '' && $lname != '' && $roll != ''){
                $result = updateStudent( $id, $fname, $lname, $roll );
                if($result){
                    header('location: /index.php?action=report');
                }else{
                    $error = 1;
                }
            }
        }else{
            if( $fname != '' && $lname != '' && $roll !='' ){
                $result = addStudent( $fname, $lname, $roll );
                if( $result ){
                    header('location: /index.php?action=report');
                }else{
                    $error = 1;
                }
            }
        }
    }

    if( 'delete' == $action ){
        $id    = filter_input( INPUT_GET, 'id', FILTER_UNSAFE_RAW );
        if($id>0){
            deleteStudent($id);
            header('location: /index.php?action=report');
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System using PHP without Database</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>
<body>

<div class="container" style="padding-top: 60px;padding-bottom:60px;">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <h2>STMS Project Using PHP with Local Datas</h2>
            <p>A sample project to perform CRUD operations using plain files and PHP</p>
            <p>
                <a href="/index.php?action=report">All Students</a> |
                <a href="/index.php?action=add">Add New Student</a> |
                <a href="/index.php?action=seed">Seed</a>
            </p>
            <?php 
            if( $info != '' ){
                echo "<p>{$info}</p>";
            }
            ?>
        </div>
    </div>
    <?php
        if( '1' == $error ): ?> 
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Duplicate Roll Number
                </blockquote>
            </div>
        </div>
    <?php 
        endif;
        if( 'report' == $action ):
    ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h4>All Students</h4>
                <?php generateReport(); ?>
            </div>
        </div>
    <?php endif; 
    if ( 'add' == $action ): ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h4>Add form for student</h4>
                <form action="/index.php?action=add" method="POST">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>">
                    <label for="roll">Roll</label>
                    <input type="number" name="roll" id="roll" value="<?php echo $roll; ?>">
                    <button type="submit" class="button-primary" name="submit">Save</button>
                </form>
            </div>
        </div>
    <?php endif;
        if( 'edit' == $action ):
            $id = filter_input( INPUT_GET, 'id', FILTER_UNSAFE_RAW );
            $student = getStudent($id);
            if( $student ):
    ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h4>Edit Students info </h4>
               <form method="POST">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
                    <label for="roll">Roll</label>
                    <input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
                    <button type="submit" class="button-primary" name="submit">Update</button>
                </form>
            </div>
        </div>
    <?php 
    endif;
endif; ?>
</div>
    
</body>
</html>