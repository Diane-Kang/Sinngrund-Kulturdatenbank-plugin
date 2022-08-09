<div class="basic_info_box">
    <style scoped>
        .basic_info_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
            padding-left: var(--wp--custom--spacing--outer);
            padding-right: var(--wp--custom--spacing--outer);
        }

        .basic_info_field{
            display: contents;
            margin-top: 100px;
            
        }

    </style>
    <p class="meta-options basic_info_field">
        <label for="latitude_longitude">latitude, longitude</label>
        <input  id="latitude_longitude" type="text" name="latitude_longitude"
                value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'latitude_longitude', true ) ); ?>"
        >
    </p>
    <p class="meta-options basic_info_field">
        <label for="latitude">Breitengrad(latitude)</label>
        <input  id="latitude" type="text" name="latitude"
                value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'latitude', true ) ); ?>"
        >
    </p>
    <p class="meta-options basic_info_field">
        <label for="longitude">Längengrad(longitude)</label>
        <input id="longitude" type="text" name="longitude"
                value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'longitude', true ) ); ?>"
        >
    </p>
    <p class="meta-options basic_info_field">
        <label for="popuptext">text for marker</label>
        <input id="popuptext" type="text" name="popuptext"
                value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'popuptext', true ) ); ?>"
                >
    </p>
</div>