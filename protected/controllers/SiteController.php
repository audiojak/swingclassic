<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
	
	public function actionRegiser()
	{
		// allows people with the correct registration code to register for the competition without logging in.
		
		if ($_GET['code']){
			$competition = Competitions::model()->findByAttributes(array('CODE' => $_GET['code'],'REGISTRATION' => 1));
			if ($competition){
				$model=new RegistrationForm;
				// render registration form
				$this->render('register',array('model' => $model,'competition' => $competition));
				
			} else {
				// render code entry form
				$this->render('codeForm');
			}
			
		} elseif ($_POST['code']) {
			$competition = Competitions::model()->findByAttributes(array('CODE' => $_POST['code'],'REGISTRATION' => 1));
			if ($competition){
				// process registration form
				
				// render thankyou
			} else {
				// render code entry form
				$this->render('codeForm');
			}
		} else {
			// render code entry form
			$this->render('codeForm');
		}
		
		// if the code is found render the form
		
		// if the post is registered and includes a valid registration code process the form and render the thank you page
		
		
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	///**
	// * Displays the login page
	// */
	//public function actionLogin()
	//{
	//	$model=new LoginForm;
	//
	//	// if it is ajax validation request
	//	if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
	//	{
	//		echo CActiveForm::validate($model);
	//		Yii::app()->end();
	//	}
	//
	//	// collect user input data
	//	if(isset($_POST['LoginForm']))
	//	{
	//		$model->attributes=$_POST['LoginForm'];
	//		// validate user input and redirect to the previous page if valid
	//		if($model->validate() && $model->login())
	//			if (isset(Yii::app()->session['comp'])){
	//				$this->redirect(Yii::app()->user->returnUrl);
	//			} else {
	//				$this->redirect('/competitions/choose');
	//			}
	//	}
	//	// display the login form
	//	$this->render('login',array('model'=>$model));
	//}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}