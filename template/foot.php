<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>

<div class="popup info" id="geeting_info_popup">
  <div class="content_wrapper">
    <img class="welcome_image"
      src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/Banner Kulturdatenbank.png"
      alt="Bilder aus dem Sinngrund">
    <button class="close close_icon" aria-label="Close"></button>
    <div class="slide one show">
      <div class="left">
        <h2><span class="d_blue">Willkommen in der</span><span class="l_blue">Kulturdatenbank Sinngrund</span>
        </h2>
        <p>
          Hier sammeln und kartografieren wir den kulturellen Schatz
          dieser Region. Sie möchten etwas beitragen und Ihr Wissen mit
          anderen teilen? <a href="mailto:info@kultur-datenbank-sinngrund.de">Kontaktieren Sie uns.</a>
        </p>
        <p>
          Wenn Sie diese Seite zum ersten Mal besuchen, sehen Sie sich
          einfach die kurze Einführung in die Kulturdatenbank an:
        </p>
        <button class="einfuehrung next">Einführung ansehen</button>
        <a class="mitmachen button" target="_blank" href="<?php echo site_url("zugang") ?>">Mitmachen</a>
      </div>


      <div class="right">
        <div class="wappen">
          <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/Wappen_von_Fellen.svg"
            alt="Gemeinde Fellen Wappen">
          <p>Gemeinde Fellen</p>

        </div>
        <div class="wappen">
          <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/SA_logo_vollton_blau.svg"
            alt="Sinngrundallianz Logo">

        </div>

        <button class="close text_button button" aria-label="Close">Zur Datenbank</button>
      </div>
    </div>
    <div class="slide two">

      <h2><span class="d_blue">So funktioniert die</span><span class="l_blue">Kulturdatenbank Sinngrund (2/3)</span>
      </h2>
      <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/screen_one.jpeg"
        alt="Screenshot zur Bedienung der Datenbank: Übersichtsseite">
      <p>
        Die Liste bildet alle Einträge analog zur Kartendarstellung ab. Mithilfe der Suchmaske können bestimmte
        Kulturelle Sehenswürdigkeiten Einträge schnell gefunden werden.
      </p>
      <div class="botton_row">
        <button class="prev" aria-label="Vorige Folie">Zurück</button>
        <button class="next" aria-label="Nächste Folie">Weiter</button>
      </div>
    </div>
    <div class="slide three">

      <h2><span class="d_blue">So funktioniert die</span><span class="l_blue">Kulturdatenbank Sinngrund (3/3)</span>
      </h2>
      <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/screen_two.jpeg"
        alt="Screenshot zur Bedienung der Datenbank: Übersichtsseite">
      <p>
        Die Liste bildet alle Einträge analog zur Kartendarstellung ab. Mithilfe der Suchmaske können bestimmte
        Kulturelle Sehenswürdigkeiten Einträge schnell gefunden werden.
      </p>
      <div class="botton_row">
        <button class="prev" aria-label="Vorige Folie">Zurück</button>
        <button class="close" aria-label="Schließen">Weiter</button>
      </div>
    </div>
  </div>
</div>
<?php wp_footer(  ); ?>