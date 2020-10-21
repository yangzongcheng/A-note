<?php

interface TeacherInterface
{
    public function teach();

    public function toTeach();
}

interface StudentInterface
{
    public function stud();
}

/**
 * 多实现
 * Class School
 */
class School implements TeacherInterface, StudentInterface
{
    public function teach()
    {
        // TODO: Implement teach() method.
        return __FUNCTION__;
    }

    public function toTeach()
    {
        // TODO: Implement toTeach() method.
        return __FUNCTION__;
    }

    public function stud()
    {
        // TODO: Implement stud() method.
        return __FUNCTION__;
    }
}

//一般实现
class Logic
{
    public function getTeach(TeacherInterface $teacher)
    {
        return $teacher->teach();
    }
}

//策略模式 实现
class LogicLmp
{
    public $course;

    public function __construct(StudentInterface $student)
    {
        $this->course = $student;
    }

    public function getStud()
    {
        return $this->course->stud();
    }
}


//echo (new Logic())->getTeach(new School());
echo (new LogicLmp(new School()))->getStud();