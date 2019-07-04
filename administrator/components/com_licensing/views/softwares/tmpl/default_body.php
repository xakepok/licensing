<?php
defined('_JEXEC') or die;
foreach ($this->items as  $software) :
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo $software['grid']; ?>
        </td>
        <td>
            <?php echo $software['state']; ?>
        </td>
        <td>
            <?php echo $software['software']; ?>
        </td>
        <td class="center">
            <?php echo $software['count']; ?>
        </td>
        <td class="center">
            <?php echo $software['available']; ?>
        </td>
        <td class="center">
            <?php echo ($this->reserv[$software['id']] != null) ? $this->reserv[$software['id']] : 0 ; ?>
        </td>
        <td>
            <?php echo $software['license']; ?>
        </td>
        <td>
            <?php echo $software['dat_start']; ?>
        </td>
        <td>
            <?php echo $software['dat_end']; ?>
        </td>
        <td>
            <?php echo $software['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>