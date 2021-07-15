<?php

 /**
 * This class is used to embed FullCalendar JQuery Plugin to my Yii2 Projects
 * @copyright Frenzel GmbH - www.frenzel.net
 * @link http://www.frenzel.net
 * @author Philipp Frenzel <philipp@frenzel.net>
 *
 */

namespace app\components\annex;

use Yii;
use yii2fullcalendar\CoreAsset;
use yii2fullcalendar\SchedulerAsset;
use yii2fullcalendar\ThemeAsset;
use yii2fullcalendar\yii2fullcalendar as Yii2fullcalendarYii2fullcalendar;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class fullcalendar extends Yii2fullcalendarYii2fullcalendar
{
    

    /**
    * Registers the FullCalendar javascript assets and builds the requiered js  for the widget and the related events
    */
    protected function registerPlugin()
    {
        $id = $this->options['id'];
        $view = $this->getView();

        /** @var \yii\web\AssetBundle $assetClass */
        $assets = CoreAsset::register($view);

        //by default we load the jui theme, but if you like you can set the theme to false and nothing gets loaded....
        if($this->theme == true)
        {
            ThemeAsset::register($view);
        }
	
	if (array_key_exists('defaultView',$this->clientOptions) && ($this->clientOptions['defaultView'] == 'timelineDay' || $this->clientOptions['defaultView'] == 'timelineWeek' || $this->clientOptions['defaultView'] == 'timelineMonth' || $this->clientOptions['defaultView'] == 'agendaDay'))
        {
            SchedulerAsset::register($view);
        }    

        if (isset($this->options['lang']))
        {
            $assets->language = $this->options['lang'];
        }

        if ($this->googleCalendar)
        {
            $assets->googleCalendar = $this->googleCalendar;
        }

        $js = array();

        if($this->ajaxEvents != NULL){
            $this->clientOptions['events'] = $this->ajaxEvents;
        }
	    
	if(!is_null($this->contentHeight) && !isset($this->clientOptions['contentHeight']))
        {
            $this->clientOptions['contentHeight'] = $this->contentHeight;
        }

        if(isset($this->customButtons) && !isset($this->clientOptions['customButtons']))
        {
            $this->clientOptions['customButtons'] = $this->customButtons;
        }

        if(is_array($this->header) && isset($this->clientOptions['header']))
        {
            $this->clientOptions['header'] = array_merge($this->header,$this->clientOptions['header']);
        } else {
            $this->clientOptions['header'] = $this->header;
        }

		if(isset($this->defaultView) && !isset($this->clientOptions['defaultView']))
        {
            $this->clientOptions['defaultView'] = $this->defaultView;
        }

        // clear existing calendar display before rendering new fullcalendar instance
        // this step is important when using the fullcalendar widget with pjax
        $js[] = "var calendar_sch = jQuery('#$id');"; // take backup of loading container
        $js[] = "var loading_container = jQuery('#$id .fc-loading');"; // take backup of loading container
        $js[] = "calendar_sch.empty().append(loading_container);"; // remove/empty the calendar container and append loading container bakup

        $cleanOptions = $this->getClientOptions();
        $js[] = "calendar_sch.fullCalendar($cleanOptions);";

        /**
        * Loads events separately from the calendar creation. Uncomment if you need this functionality.
        *
        * lets check if we have an event for the calendar...
            * if(is_array($this->events))
            * {
            *    foreach($this->events AS $event)
            *    {
            *        $jsonEvent = Json::encode($event);
            *        $isSticky = $this->stickyEvents;
            *        $js[] = "jQuery('#$id').fullCalendar('renderEvent',$jsonEvent,$isSticky);";
            *    }
            * }
        */

        $view->registerJs(implode("\n", $js),View::POS_READY);
    }

    /**
     * @return array the options for the text field
     */
    protected function getClientOptions()
    {
        $id = $this->options['id'];
      
	if ($this->onLoading)
            $options['loading'] = new JsExpression($this->onLoading);
        else {
	    $options['loading'] = new JsExpression("function(isLoading, view ) {
                jQuery('#{$id}').find('.fc-loading').toggle(isLoading);
	    }");
	}
                                               
        //add new theme information for the calendar                                       
		$options['themeSystem'] = $this->themeSystem;
                                               
        if ($this->eventRender){
            $options['eventRender'] = new JsExpression($this->eventRender);
        }
        if ($this->eventAfterRender){
            $options['eventAfterRender'] = new JsExpression($this->eventAfterRender);
        }
        if ($this->eventAfterAllRender){
            $options['eventAfterAllRender'] = new JsExpression($this->eventAfterAllRender);
        }

        if ($this->eventDrop){
            $options['eventDrop'] = new JsExpression($this->eventDrop);
        }
	    
        if ($this->eventResize){
            $options['eventResize'] = new JsExpression($this->eventResize);
        }	    

        if ($this->select){
            $options['select'] = new JsExpression($this->select);
        }
                                               
        if ($this->eventClick){
            $options['eventClick'] = new JsExpression($this->eventClick);
        }
        if ($this->dayClick){
            $options['dayClick'] = new JsExpression($this->dayClick);
        }

        if (is_array($this->events) || is_string($this->events)){
            $options['events'] = $this->events;
	    }elseif($this->events instanceof JsExpression){
            $options['events'] =new JsExpression($this->events);
        }

        $options = array_merge($options, $this->clientOptions);
        
        return Json::encode($options);
    }

}
