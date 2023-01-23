<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>
<div class = "popup_wrapper <?php echo  (isset($_COOKIE['KDB_visitor_visit_time']) || is_user_logged_in() ) ? 'hide_info': NULL; ?>" > 
    <div class="popup info " id="geeting_info_popup">
        <!-- <button class="mobile close close_icon" aria-label="Close"></button> -->
        <div class="content_wrapper">
            <!-- <div class="close_wrapper">
                <button class="desktop close close_icon" aria-label="Close"></button>
            </div> -->
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
                    <div class="wappen_wrapper">
                        <div class="wappen">
                            <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/Wappen_von_Fellen.svg"
                                alt="Gemeinde Fellen Wappen">
                            <p>Gemeinde Fellen</p>

                        </div>
                        <div class="wappen">
                            <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/SA_logo_vollton_blau.svg"
                                alt="Sinngrundallianz Logo">
                            <p>Gefördert durch das Regionalbudget 2022 der Sinngrundallianz mit Geldern des Amtes für ländliche Entwicklung Unterfranken.</p>

                        </div>
                    </div>
                    <button class="close text_button button" aria-label="Close">Zur Datenbank</button>
                </div>
            </div>
            <div class="slide two">

                <h2><span class="d_blue">So funktioniert die </span><span class="l_blue">Kulturdatenbank Sinngrund
                        (1/3)</span>
                </h2>
                <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/screen111.png"
                    alt="Screenshot zur Bedienung der Datenbank: Übersichtsseite">
                <p>
                Jedes Symbol auf der Karte steht für einen Eintrag in der Kulturdatenbank. Die unterschiedlichen Symbole stehen für verschiedene Kategorien, z.B. Wanderwege oder Sehenswürdigkeiten. Klicken Sie auf ein Symbol, um zum entsprechenden Detailartikel zu kommen.
                </p>
                <div class="botton_row">
                    <button class="prev" aria-label="Vorige Folie">Zurück</button>
                    <button class="next" aria-label="Nächste Folie">Weiter</button>
                </div>
            </div>
            <div class="slide three">

                <h2><span class="d_blue">So funktioniert die </span><span class="l_blue">Kulturdatenbank Sinngrund
                        (2/3)</span>
                </h2>
                <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/screen222.jpeg"
                    alt="Screenshot zur Bedienung der Datenbank: Übersichtsseite">
                <p>
                    Die Liste bildet alle Einträge analog zur Kartendarstellung ab. Mithilfe der Suchmaske können bestimmte
                    Einträge schnell gefunden werden.
                </p>
                <div class="botton_row">
                    <button class="prev" aria-label="Vorige Folie">Zurück</button>
                    <button class="next" aria-label="Schließen">Weiter</button>
                </div>
            </div>
            <div class="slide four">

                <h2><span class="d_blue">So funktioniert die </span><span class="l_blue">Kulturdatenbank Sinngrund
                        (3/3)</span>
                </h2>
                <img src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/assets/screen_two.jpeg"
                    alt="Screenshot zur Bedienung der Datenbank: Übersichtsseite">
                <p>
                Um nur bestimmte Kategorien anzuzeigen, können diese über das Filtermenü ein- oder ausgeblendet werden. Ist kein Haken gesetzt, werden automatisch alle Symbole angezeigt.
                </p>
                <div class="botton_row">
                    <button class="prev" aria-label="Vorige Folie">Zurück</button>
                    <a class="mitmachen button" target="_blank" href="<?php echo site_url("zugang") ?>">Mitmachen</a>
                    <button class="close" aria-label="Schließen">Schließen</button>
                </div>
            </div>
        </div>
        <div class="close d-button" id="d-close-button">
            <div class="close_x_mark"></div>
        </div>
        <!-- <button class="close button close_icon" id="d-close-button"></button> -->
    </div>
</div>
<?php wp_footer(  ); ?>