<?php
define('DB_NAME','data/db.txt');

function seed(){
    $data = array(
        array(
            'id' => 2,
            'fname' => 'Rakib',
            'lname' => 'hasan',
            'roll' => '22'
        ),
        array(
            'id' => 4,
            'fname' => 'Sakib',
            'lname' => 'hasan',
            'roll' => '12'
        ),
        array(
            'id' => 3,
            'fname' => 'Shafi',
            'lname' => 'khan',
            'roll' => '11'
        ),
        array(
            'id' => 1,
            'fname' => 'Asif',
            'lname' => 'Khan',
            'roll' => '9'
        ),
        array(
            'id' => 5,
            'fname' => 'Manik',
            'lname' => 'Mia',
            'roll' => '32'
        )
    );
    $serializedData = serialize($data);
    file_put_contents(DB_NAME,$serializedData, LOCK_EX);
}

function generateReport(){
    $getDataSerialized = file_get_contents( DB_NAME );
    $students = unserialize($getDataSerialized);
    // echo '<pre>';
    // var_dump($students);
    // echo '</pre>';
    ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th width="25%">Action</th>
            </tr>
            <?php foreach($students as $student){ ?>
            <tr>
                <td><?php printf('%s %s', $student['fname'], $student['lname']); ?></td>
                <td><?php printf('%s', $student['roll']); ?></td>
                <td><?php printf('<a href="/index.php?action=edit&id=%s">Edit</a> | <a class="delete" href="/index.php?action=delete&id=%s">Delete</a>', $student['id'], $student['id']); ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php 
}
function addStudent( $fname, $lname, $roll ){
    $found = false;
    $getDataSerialized = file_get_contents( DB_NAME );
    $students = unserialize($getDataSerialized); 
    foreach($students as $_student){
        if( $_student['roll'] == $roll ){
            $found = true;
            break;
        }        
    }
    if( !$found ){
        $newId = getNewId($students);
        $student = array(
            'id' => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll' => $roll
        );
        array_push( $students, $student );
        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );
        return true;
    }
    return false;  
}
function getNewId($students){
    $maxId = max(array_column($students, 'id'));
    return $maxId+1;
}
function getStudent( $id ){
    $serializedData = file_get_contents( DB_NAME );
    $students = unserialize($serializedData);
    foreach( $students as $student ){
        if( $student['id'] == $id ){
            return $student;
        }
    }
    return false;
}
function updateStudent( $id, $fname, $lname, $roll ){
    $found = false;
    $serializedData = file_get_contents( DB_NAME );
    $students = unserialize($serializedData);
    foreach( $students as $_student ){
        if( $_student['roll'] == $roll && $_student['id'] != $id ){
            $found = true;
            break;
        }
    }
    if( ! $found ){
        $students[ $id-1 ]['fname'] = $fname;
        $students[ $id-1 ]['lname'] = $lname;
        $students[ $id-1 ]['roll'] = $roll;
        $serializedData      = serialize($students);
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );
        return true;
    }
    return false;
}

function deleteStudent($id){
    $serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
    foreach( $students as $key=>$value ){
        if( $value['id'] == $id ){
            unset($students[$key]);
        }
    }
    $serializedData = serialize($students);
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}