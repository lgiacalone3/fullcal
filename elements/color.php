<?php

defined('JPATH_BASE') or die();

class JFormFieldColor extends JFormFieldList
{

    function getInput()
    {
        ob_start();
        $jquery = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/jquery/jquery-1.8.1.min.js";
        $jspath = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/spectrum/spectrum.js";
        $css2 = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/spectrum/spectrum.css";
        ?>

    <script src="<?php echo $jquery;?>"></script>
    <script src="<?php echo $jspath;?>"></script>
    <link href="<?php echo $css2;?>" type="text/css" rel="stylesheet"/>
    <input name="<?php echo $this->name;?>" type="text" class="color" value="<?php echo $this->value; ?>"/>
    <br/>
    <script>
        countcolors = $(".color").size();
        if (countcolors == 6){
            $(".color").spectrum({
                preferredFormat: "hex",
                showInput: true
            });
            var header =  $("b:contains('Custom Colors')").parent().parent().parent().parent();
            header.append('<br/><br/><dfn>When applying color based on event duration, only the first two colors will be used<br/>Color #1 is used for single day events, Color #2 is used for multi-day events</dfn><br/><br/>');
        }
    </script>
    <?php
        $content=ob_get_contents();
        ob_end_clean();
        return $content;

    }

    function fetchElement($name, $value, &$node, $control_name)
    {

        $document = & JFactory::getDocument();
        $document->addScript(JURI::base(). "modules/mod_civicrm_fullcalendar/fullcalendar/spectrum/spectrum.js");

        ob_start();
        $jquery = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/jquery/jquery-1.8.1.min.js";
        $css2 = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/spectrum/spectrum.css";
        $jspath = JUri::root() . "modules/mod_civicrm_fullcalendar/fullcalendar/spectrum/spectrum.js";
        ?>
        <link href="<?php echo $css2;?>" type="text/css" rel="stylesheet"/>
        <script src="<?php echo $jquery;?>"></script>
        <script src="<?php echo $jspath;?>"></script>
        <script>
            $(document).ready(function () {
                $(".color").spectrum();
            });
        </script>
        <?php

        ?>
    <label>
        <input name="<?php echo $name;?>" type="text" class="color" value="<?php echo $value;?>"/>
    </label>
    <?php

        $content = ob_get_contents();

        ob_end_clean();
        return $content;

    }
}