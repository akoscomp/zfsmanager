<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$path = 'stripe/template/';

include_once 'include.php';

if(isset($_POST['volume']) && isset($_POST['action'])) {
    $volume = $_POST['volume'];
    $action = $_POST['action'];
    $id = rand(1000, 9999);
  if( $action === 'clone') {
      if ( (strpos($volume, '_snap') !== false) || (strpos($volume, '_clone') !== false)) {
          if ( (strpos($volume, '_snap') !== false) ) {
            
            $command2 = 'sudo zfs clone '.$volume.' '.clearVolumeName($volume).'_clone_'.$id;
            exec($command2,$output,$error);
            $message = 'Success, create a snaphost clone: '.clearVolumeName($volume).'_clone_'.$id;
            echo $message;
            zfslog($message);
          }
          if ( (strpos($volume, '_clone') !== false) && (strpos($volume, '_snap') == false) ) {
            echo 'Error: this subvolume is a clone';
          }
      } else {
        $command1 = 'sudo zfs snapshot '.$volume.'@_snap_'.$id;
        $command2 = 'sudo zfs clone '.$volume.'@_snap_'.$id.' '.$volume.'_clone_'.$id;
        exec($command1,$output,$error);
        exec($command2,$output,$error);
        $message = 'Success, volume: '.$volume.' is cloned to '.$volume.'_clone_'.$id;
        echo $message;
        zfslog($message);
      }
  } elseif ( $action === 'snapshot') {
    $command1 = 'sudo zfs snapshot '.$volume.'@_snap_'.$id;
    exec($command1,$output,$error);
    $message = 'Success, snapshot "'.$volume.'@_snap_'.$id.'" created.';
        echo $message;
        zfslog($message);
    
  } elseif ( $action === 'delete') {
      if ( (strpos($volume, '_snap') !== false) || (strpos($volume, '_clone') !== false)) {
        $command1 = 'sudo zfs destroy -R '.$volume;
        exec($command1,$output,$error);
        $message = 'Success, "'.$volume.'" deleted.';
        echo $message;
        zfslog($message);
      } else {
          echo 'Error: NOT a snapshot or clone!';
      }
  } elseif ( $action === 'create') {
      if ( testVolumeName($volume) ) {
        $command1 = 'sudo zfs create '.$path.$volume;
        exec($command1,$output,$error);
        $message = 'Success, "'.$path.$volume.'" created.';
        echo $message;
        zfslog($message);
      } else {
          echo 'Error: NOT a valid volume name.';
      }
  } elseif ( $action === 'rename') {
     if (isset($_POST['newVolumeName']) && isset($_POST['oldVolumeName'])) {
      $newVolumeName = $_POST['newVolumeName'];
      $oldVolumeName = $_POST['oldVolumeName'];
      if ( testVolumeName($newVolumeName) ) {
        $command1 = 'sudo zfs rename "'.$oldVolumeName.'" "'.$newVolumeName.'"';
        exec($command1,$output,$error);
        $message = 'Success: '.$command1;
        echo $message;
        zfslog($message);
      } else {
          echo 'Error: NOT a valid volume name.';
      }
     }
   } else {
      echo 'Error';
   }
}


function testVolumeName($volume) {
    $result = true;
    if ( $volume == "" ) {
        $result = false;
    }
    if ( ! preg_match('/^[A-Za-z0-9@_\/-]*$/', $volume)) {
        $result = false;
    }
    return $result;
}

function clearVolumeName($volume) {
    return substr($volume, 0, strpos($volume, "@"));
}
