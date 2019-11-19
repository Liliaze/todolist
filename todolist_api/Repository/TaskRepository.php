<?php
/**
 * Created by PhpStorm.
 * User: Liliaze
 * Date: 12/11/2019
 * Time: 13:24
 */

require_once './Repository/PdoHelper.php';
require_once './Model/TaskModel.php';

class TaskRepository extends PdoHelper
{
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new TaskRepository();
        return self::$instance;
    }

    public function createTask($taskModel) {
        $request =  parent::getPdo()->prepare("INSERT INTO task (id_task, id_tasklist, id_user, content, status, created,updated) VALUES (null, ?, ?, ?, ?, NOW(), NOW())");
        $result = $request->execute(array($taskModel->getIdTaskList(), $taskModel->getIdUser(), $taskModel->getContent(), $taskModel->getStatus()));
        if (!$result)
            throw new UnknownException('taskList not created');
        return $result;
    }

    public function getAllTasksInList($taskListId) {
        $request =  parent::getPdo()->prepare("SELECT * FROM `task` WHERE id_tasklist LIKE :id");
        $request->bindParam(":id",$taskListId);
        $success = $request->execute();
        if (!$success)
            return $success;
        $pdoresults = $request->fetchAll();
        $taskArray = [];
        foreach ($pdoresults as $key => $task) {
            $newTask = new TaskModel();
            $newTask->unserialize($task);
            $taskArray[$key] = $newTask->serialize();
        }
        return $taskArray;
    }

    public function getTaskById($taskId) {
        $request =  parent::getPdo()->prepare("SELECT * FROM `task` WHERE `id_task` = :id");
        $request->bindParam(":id",$taskId);
        $success = $request->execute();
        if (!$success)
            throw new UnknownException('Task not created');
        $pdoresults = $request->fetchAll();
        $newTask = new TaskModel();
        if ($pdoresults != null && isset($pdoresults[0])) {
            $newTask->unserialize($pdoresults[0]);
        }
        return $newTask;
    }

    public function updateTask($taskModel) {
        $request =  parent::getPdo()->prepare("UPDATE `task` SET `id_tasklist` = ?, `content` = ?, `status` = ?, updated = NOW() WHERE `task`.`id_task` = ?;");
       /* $request->bindParam(":id",$taskModel->getIdTask());
        $request->bindParam(":c",$taskModel->getContent());
        $request->bindParam(":s",$taskModel->getStatus());
        $request->bindParam(":idtasklist",$taskModel->getIdTaskList());*/
        $success = $request->execute(array($taskModel->getIdTaskList(), $taskModel->getContent(),$taskModel->getStatus(), $taskModel->getIdTask()));
        return $success;
    }
/*
    public function deleteTaskList($taskListId) {
        $request =  parent::getPdo()->prepare("DELETE FROM `tasklist` WHERE `tasklist`.`id_tasklist` = :id");
        $request->bindParam(":id",$taskListId);
        $pdoresults = $request->execute();
        if (!$pdoresults)
            throw new Exception('taskList not deleted');
        return $pdoresults;
    }

    public function getTaskList($userId) {
        $request =  parent::getPdo()->prepare("SELECT * FROM `tasklist` WHERE id_user LIKE :u");
        $request->bindParam(":u",$userId);
        $request->execute();
        $pdoresults = $request->fetchAll();
        $taskListArray = [];
        foreach ($pdoresults as $key => $taskList) {
            $newTaskList = new TaskListModel();
            $newTaskList->setTaskListByRequest($taskList);
            $taskListArray[$key] = $newTaskList;
        }
        return $taskListArray;
    }

    public function getTaskListById($taskListId) {
        $request =  parent::getPdo()->prepare("SELECT * FROM `tasklist` WHERE `id_tasklist` = :t");
        $request->bindParam(":t",$taskListId);
        $request->execute();
        $pdoresults = $request->fetchAll();
        $newTaskList = new TaskListModel();
        if ($pdoresults != null && isset($pdoresults[0])) {
            $newTaskList->setTaskListByRequest($pdoresults[0]);
        }
        return $newTaskList;
    }
*/
}