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

        //mysql query for category types
        $query = "SELECT civicrm_option_value.value, civicrm_option_value.label
            FROM civicrm_option_value
            INNER JOIN
                civicrm_option_group
            ON (civicrm_option_value.option_group_id = civicrm_option_group.id)
            WHERE (civicrm_option_group.name = 'event_type')
            AND (civicrm_option_value.is_active = '1')";
        $result = mysql_query($query) or die(mysql_error());
        ?>

    <script src="<?php echo $jquery;?>"></script>
    <script src="<?php echo $jspath;?>"></script>
    <link href="<?php echo $css2;?>" type="text/css" rel="stylesheet"/>
    <div style="height:20px"></div>

    <?php
        $n = 0;

        $data = $this->value;
        $pieces = explode(" ", $data);
        $backuparray = $pieces;
        while ($row = mysql_fetch_array($result)) {

            ?>
        <br/>
        <div style="display: inline-block;width: 100px;"><?php echo $row[label];?></div>
        <input name="<?php echo $row[label];?>" id="<?php echo $n; ?>" type="text" class="color"
               value="<?php echo $pieces[$n]; ?>"/>
        <br/>
        <?php

            $n++;
        }

        ?>
    <br/>
    <a href="javascript:void(0)" id="toggle">Show/Hide Text Field</a>
    <div id="output">
        <br/>
        <input name="<?php echo $this->name;?>" type="text" value="<?php echo $this->value; ?>"/>
    </div>
    <script>
        $("a#toggle").click(function () {
            $("#output").toggle('fast');
        });
        $(window).load(function () {
            $("#output").hide();
            $(".color").spectrum({
                preferredFormat:"hex",
                showInput:true,
                clickoutFiresChange:true,
                showInitial:true,
                change:function (tinycolor) {
                    update();
                }
            });
        });
        function update() {
            var out = "";
            $("input.color").each(function () {
                out += $(this).val() + " ";
            });
            out = $.trim(out);
            $("input#output").val(out)
        }
    </script>
    <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;

    }

    function fetchElement($name, $value, &$node, $control_name)
    {
        ob_start();
        ?>
    <label>
        <input name="<?php echo $name;?>" type="text" value="<?php echo $value;?>"/>
    </label>
    <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function getLabel()
    {
        return null;
    }
}