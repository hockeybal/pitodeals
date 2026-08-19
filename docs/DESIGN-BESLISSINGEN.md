# PITO Deals — actuele ontwerpbeslissingen

## Positionering

- PITO Deals gebruikt de belofte **Ontdek je voordeel** en verwijst zichtbaar naar de PITO-app.
- De vaste merklock-up gebruikt het officiële, opgeschoonde PITO-SVG met **PITO** en de productbelofte **Ontdek je voordeel**. **Deals** is geen onderdeel van het logo.
- De afzenderregel **Een initiatief van PITO · Ontdek je buurt** staat los van het logo in de footer. Zo blijft de hoofdlock-up in de navigatie rustig en correct.
- App-downloadacties gebruiken overal de korte CTA **Download PITO**.
- Deals, collectieven en vacatures blijven onderdelen van één PITO-omgeving. Er komt geen losse vacaturesite.
- De sociale missie van PITO blijft de basis; commercieel voordeel helpt bewoners en lokale economie vooruit.

## Gemeentecontext

- De gekozen gemeente blijft zichtbaar in de vaste header als plaatsnaam met een neerwaartse pijl; de hele knop is klikbaar.
- De vaste header gebruikt een compacte gemeenteknop met een duidelijke neerwaartse pijl; de hele knop opent de selector.
- De gemeente blijft behouden wanneer iemand navigeert tussen deals, collectieven, vacatures en andere pagina's.
- Eén gedeelde gemeentevoorkeur stuurt header, deals, vacatures, updates en footer aan. Een keuze in Woerden blijft dus Woerden op iedere vervolgpagina.
- Het wijzigen van de gemeente gebeurt in de selector vanuit de header of hero, niet op een aparte pagina.
- In de homepagehero staat bij een gekozen gemeente alleen de plaatsnaam met een neerwaartse pijl. De zichtbare tekst “Wijzig gemeente” wordt niet gebruikt; de toegankelijke naam blijft wel aangeven dat de knop de gemeente wijzigt.

## E-mailupdates

- Alleen op de homepage staat onder de navigatie een compacte updates-CTA. Het volledige formulier opent na een bewuste klik. Op overzichts- en detailpagina's krijgt de primaire taak voorrang; de uitgebreide inschrijving blijft daar in de footer of in een inhoudelijk passend blok beschikbaar.
- De activatie gebruikt geen generiek “Blijf op de hoogte”, maar benoemt de concrete waarde: nieuwe deals en vacatures voor de gekozen gemeente. De CTA luidt **Kies mijn updates** en de definitieve formulieractie **Activeer mijn updates**.
- De gebruiker kiest gemeente en voorkeur: deals, vacatures of beide.
- Inschrijving vereist expliciete toestemming en verwijst naar de privacyverklaring.

## Collectieven

- Collectiefpagina's sturen naar één interne PITO-adviesaanvraag. Er is geen zichtbare rekentool en geen concurrerende externe partner-CTA.
- De partnernaam is vóór verzending niet zichtbaar. De gebruiker ziet alleen dat PITO een specialist heeft geselecteerd; de naam verschijnt in het bedankscherm.
- In productie mag de publieke Strapi-response daarom geen partnernaam bevatten. Na succesvolle leadregistratie retourneert een beveiligde backend-response de naam voor het bedankscherm.
- Deze uitgestelde bekendmaking en de toestemmingscopy moeten vóór livegang juridisch/privacytechnisch worden gevalideerd.
- Het formulier vraagt alleen: voornaam, achternaam, e-mail, telefoonnummer en volledig woningadres.
- Hoofd-CTA: **Ontvang gratis advies voor jouw woning**.
- PITO deelt gegevens met maximaal één duidelijk genoemde partner en alleen na toestemming.
- Na verzending ziet de gebruiker welke partner persoonlijk contact opneemt. Niet-bevestigde serviceniveaus, zoals “binnen 48 uur”, worden niet beloofd.
- Als een partner nog niet definitief is, bewaart PITO alleen de interesse en deelt nog geen gegevens.
- Individuele proposities heten **Warmtepomp Collectief**, **Thuisbatterij Collectief** en **Isolatie Collectief**. PITO is de selecterende en verbindende laag, niet de uitvoerende installateur.
- De collectiefcopy legt drie mogelijke bundelvoordelen uit: slimmer plannen, kosten beperken en meer zekerheid. Een prijsbesparing wordt nooit vooraf gegarandeerd; de persoonlijke offerte blijft leidend.
- De conversieroute is verkort: elk collectief op het overzicht heeft naast **Bekijk collectief** een directe adviesactie.
- Hoofd-CTA's in de hero, partnersectie, kaarten, onderste conversiesectie en mobiele balk openen hetzelfde korte adviesformulier als modal. Zo is inschrijven overal één klik verwijderd zonder een permanent formulier in beeld.
- Op mobiel blijft een compacte advies-CTA onderin beschikbaar.
- De losse aanvraagroute blijft bestaan voor campagnes en directe links, maar gebruikt geen zware linkerkolom meer. Introductie en proces staan boven een centraal formulier.
- Ieder collectief begint met een concrete bewonersvraag, zoals **Past een warmtepomp bij jouw woning?** De toon is menselijk en activerend, maar vermijdt grappen bij financiële of technische claims.
- De partnerselectiekaarten tonen nu direct het type collectief, een herkenbare praktijkfoto en een eigen PITO-accentkleur. Een korte entree- en hoverbeweging geeft interactie aan zonder de rustige merkuitstraling te verstoren.
- Op middelgrote schermen stapelt de collectiefhero al onder 1020 px. Zo blijft de titel leesbaar en verandert deze niet in een smalle, hoge tekstmuur.
- Bevestigde collectieven gebruiken overal dezelfde conversielijn: **Plan gratis advies**. De modal begint met **Plan gratis advies voor jouw woning** en legt uit dat maximaal één zorgvuldig geselecteerde specialist contact opneemt.
- Partnernamen blijven vóór registratie buiten beeld. Pas op de bedankbevestiging ziet de gebruiker welke partner contact opneemt.
- **Alles** op de dealspagina toont daadwerkelijk het complete voordeeloverzicht: eerst alle relevante lokale deals, daarna collectieven en vervolgens het landelijke aanbod.
- De resultaatteller telt ook de zichtbare collectieven mee en gebruikt daarom de bredere term **voordelen**. In de huidige Woerden-testopzet zijn dat vijf lokale deals, drie collectieven en vier landelijke voordelen: twaalf in totaal. De teller wordt altijd uit de zichtbare Strapi-records berekend en nooit als vast getal beheerd.

## Vacatures

- PITO Vacatures is een volwaardig onderdeel van dezelfde website en app, geen los merk of aparte site.
- De hoofdtypen zijn: Alles, Full-time, Part-time, Bijbaan, Stage en Vrijwilligerswerk.
- Op het hoofdoverzicht worden vacatures in rijen getoond; een actief type toont een volledig resultatenoverzicht.
- Iedere vacature-rij heeft een eigen herkenbare kleurzone. Full-time gebruikt een rustig oranje vlak en oranje kaartaccenten.
- Vacaturekaarten volgen dezelfde visuele logica als dealkaarten en openen een eigen detailpagina.
- De gekozen gemeente wordt gedeeld met Deals en blijft actief bij navigatie. Als lokaal aanbod ontbreekt, blijven landelijke vacatures zichtbaar en wordt de lokale status transparant uitgelegd.
- Vacatures worden later vanuit dezelfde Strapi-bron tegelijk op web en in de app gepubliceerd.
- Werkgevers krijgen op het vacatureoverzicht een prominente eigen conversiesectie. Nieuwe bedrijven gaan naar `pito.app/signup`; bestaande zakelijke gebruikers kunnen rechtstreeks inloggen.

## Zakelijke conversie

- **Voor ondernemers** is een vaste hoofdnavigatiebestemming en opent een eigen zakelijke landingspagina.
- De zakelijke pagina begint bij de PITO-missie: bewoners, organisaties, gemeenten en lokale ondernemers samenbrengen in één privacy-first buurtomgeving. Zakelijke zichtbaarheid wordt gepositioneerd als relevante buurtwaarde, niet als losse advertentieruimte.
- De homepage toont een volwaardige zakelijke ingang met twee gescheiden proposities: **Vacatures plaatsen** en **Deals & aanbod tonen**.
- De categorie **Voor ondernemers** combineert consumentgericht zakelijk voordeel met een afzonderlijke pitch voor ondernemers die zelf aanbod willen publiceren.
- Zakelijke CTA's maken het proces vooraf concreet: account aanmaken, plaatsing kiezen en online afrekenen.
- Registratie en inloggen blijven onderdeel van de bestaande PITO-omgeving. Er wordt geen apart account- of betaalsysteem voor PITO Deals ontworpen.
- De zakelijke pagina gebruikt inhoud uit de actuele bedrijfsbrochure, maar neemt het brochuredesign niet over. De kern bestaat uit lokale zichtbaarheid, activiteiten, aanbiedingen, vacatures en de maatschappelijke tegenprestatie voor lokale organisaties.
- Pakketten, prijzen, betaling en bijbehorende webhooks worden niet gedupliceerd. Alle CTA's verwijzen eerst naar de primaire zakelijke PITO-pagina; daar blijven actuele proposities en checkout centraal beheerd.
- De pakketprijzen worden wél informatief getoond om de lage instapdrempel zichtbaar te maken: Starter €30 per maand/€24 bij jaarbetaling, Groei €50/€40 en Pro €100/€80. Bij iedere prijs staat dat actuele voorwaarden op pito.app gelden; checkout en webhooks blijven daar.
- Snelheid is een hoofd-USP: een vacature of aanbieding kan in ongeveer één minuut worden gepubliceerd. Ondernemers kiezen zelf tussen langdurig generiek aanbod en wisselende acties, bepalen de looptijd en ontvangen een e-mail wanneer een plaatsing verloopt.
- Bereik wordt conversiegericht maar verdedigbaar omschreven. PITO benoemt de app, sociale kanalen, aanvullende lokale campagnes en privacyvriendelijke benadering van nieuwe inwoners. De website belooft niet letterlijk iedere inwoner te bereiken en toont een korte resultaatdisclaimer.
- Direct na de zakelijke hero staat een concreet opbrengstblok rond vier uitkomsten: lokaal klantbereik, sollicitanten dichtbij, relevant zichtbaar worden voor nieuwe inwoners en tijdwinst bij publiceren. Views of omzet worden niet gegarandeerd; PITO maakt vindbaar en stuurt door naar het door de ondernemer gekozen eindpunt.

## Navigatie en filters

- De vaste header is de enige plek voor het wijzigen van de gemeente buiten de homepagehero; filterbalken tonen geen tweede gemeenteselector.
- De hoofdnavigatie scheidt de drie productingangen **Deals**, **Collectieven** en **Vacatures**. Deze worden op vervolgpagina's niet opnieuw gemengd met de inhoudelijke Strapi-categorieën.
- Deals gebruiken één horizontale balk met uitsluitend dealcategorieën uit dezelfde Strapi-taxonomie. **Vaste lasten** is een vaste redactionele ingang naar de bijbehorende Strapi-selectie, geen tweede datastructuur.
- Vacatures gebruiken één horizontale typebalk met de bestaande employment-types. Er is geen extra sorteer- of filtermenu zolang het aanbod per gemeente overzichtelijk blijft.
- Bereik wordt niet als filter herhaald: de gekozen gemeente bepaalt lokaal aanbod en landelijk aanbod vult dit automatisch aan. De speciale link **Landelijk voordeel** gebruikt dezelfde records met alleen `is_national`.
- Bij een gekozen gemeente toont het standaard dealoverzicht alleen aanbod voor die gemeente plus landelijk aanbod; lokaal aanbod uit andere gemeenten wordt niet gemengd.
- Op listingpagina's is de hero compacter dan op de homepage, zodat categorieën en aanbod eerder in beeld komen. Collectieven behouden een rijkere hero omdat daar uitleg en vertrouwen onderdeel van de conversie zijn.
- Deal- en vacaturedetails hebben op mobiel één vaste primaire actie onderin. Zo blijft doorsturen of aanmelden bereikbaar zonder een tweede concurrerende CTA.

## Over PITO

- **Hoe werkt PITO?** en **Over PITO** zijn samengevoegd tot één pagina en één hoofdmenu-item. De oude route blijft dezelfde pagina tonen zodat bestaande links blijven werken.
- De pagina gebruikt de bedrijfsbrochure als inhoudelijke bron: eerst het lokale zichtbaarheidsprobleem, daarna de letterlijke missie en visie, vervolgens de waarde voor organisaties, ondernemers en inwoners. Dubbele generieke stappenblokken zijn verwijderd.
- De echte PITO-appschermen worden gebruikt als bewijs; er worden geen fictieve appschermen gegenereerd.
- Het appblok toont drie echte schermen naast elkaar: buurtberichten, activiteiten/agenda en buurtchat.

## Responsive uitgangspunt

- Er is één responsive website in plaats van een aparte mobiele site.
- De belangrijkste controlebreedte is circa 390 px, met compacte navigatie, bruikbare tikvlakken, éénkoloms formulieren en horizontaal beweegbare kaart- of schermrijen waar nodig.
- Dezelfde Strapi-contentstructuur voedt desktop en mobiel; er komt geen tweede content- of paginabeheerlaag.

## Kleurgebruik

- De exacte PITO-kleuren uit het vaste palet worden gebruikt: blauw, cyaan, oranje, teal, paars en roze met de vastgelegde lichte en donkere varianten.
- Blauw en warm wit blijven de basis voor rust en betrouwbaarheid. Teal, cyaan, paars en roze worden functioneel gebruikt voor collectieven, vacatures, categorieën en processtappen.
- De footer bestaat uit duidelijke kleurzones: warm wit voor updates, PITO-blauw voor de app, donkerblauw voor navigatie en ink voor de afsluitende regel.

## Typografie

- Alle schermen, formulieren en componenten gebruiken Poppins.
- De lettertypebestanden worden lokaal met de website meegeleverd, zodat de vormgeving niet afhankelijk is van een externe fontdienst.
- Koppen worden waar mogelijk als betekenisvolle zinnen geschreven en typografisch gebalanceerd; alinea's gebruiken prettige regelafbreking om losse laatste woorden en scheve tekstblokken te beperken.

## Copyprincipes

- Complexe keuzes beginnen met een herkenbare vraag en eindigen met een rustige vervolgstap: eerst begrijpen, dan beslissen.
- CTA's benoemen de concrete actie of uitkomst, bijvoorbeeld **Plan gratis advies** en **Bekijk de volledige vacature**.
- PITO-tekst mag licht en menselijk zijn, maar blijft professioneel: korte zinnen, geen overdreven claims en altijd duidelijk wie verantwoordelijk is.
- De stijl gebruikt dezelfde klantgerichte principes als sterke Nederlandse servicecopy, zonder bestaande pay-offs of formuleringen letterlijk over te nemen.

## Technische bron

- Gemeente-, categorie-, deal- en collectiefdata worden later vanuit dezelfde Strapi-bron aan app en website geleverd.
- Lokale leegte wordt opgevangen met landelijke deals en collectieven, zonder dat lege categorieën de homepage domineren.
- Kaarten tonen rechtstreeks uit Strapi minimaal: voordeel, afbeelding, titel, aanbieder, gemeente/landelijk bereik, beschikbaarheidsvorm en status. De UI leidt labelkleuren semantisch af; redacteuren beheren geen losse kleuren.
