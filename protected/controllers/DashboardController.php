<?php

class DashboardController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
    {
        return array(
            array('deny',
                'users'=>array('?'),
            ),
            array('allow',
                'actions'=>array('*'),
                'roles'=>array('@'),
            ),
        );
    }
	
}