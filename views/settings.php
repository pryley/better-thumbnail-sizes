<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <td class="td-full" style="padding-top:0;">
                <label for="<?= $name; ?>">
                    <input type="checkbox" id="<?= $name; ?>" name="<?= $name; ?>" value="1" <?= $checked_attribute; ?>/>
                    <?= __('Place the image sizes in a sub-directory', 'better-thumbnail-sizes'); ?>
                </label>
            </td>
        </tr>
    </tbody>
</table>
