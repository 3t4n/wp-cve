// Admin menu
function Total_Soft_Portfolio_AMD2_But1(Portfolio_ID) {
  jQuery('.Total_Soft_Portfolio_AMD2').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_PortfolioAMMTable').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_PortfolioAMOTable').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_Portfolio_Save').animate({ 'opacity': 1 }, 500);
  jQuery('.Total_Soft_Portfolio_Update').animate({ 'opacity': 0 }, 500);
  jQuery('#Total_Soft_Portfolio_ID').html('[Total_Soft_Portfolio id="' + Portfolio_ID + '"]');
  jQuery('#Total_Soft_Portfolio_TID').html('&lt;?php echo do_shortcode(&#039;[Total_Soft_Portfolio id="' + Portfolio_ID + '"]&#039;);?&gt');
  Total_Soft_Portfolio_Editor();
  setTimeout(function () {
    jQuery('.Total_Soft_Portfolio_AMD2').css('display', 'none');
    jQuery('.Total_Soft_PortfolioAMMTable').css('display', 'none');
    jQuery('.Total_Soft_PortfolioAMOTable').css('display', 'none');
    jQuery('.Total_Soft_Portfolio_Save').css('display', 'block');
    jQuery('.Total_Soft_Portfolio_Update').css('display', 'none');
    jQuery('.Total_Soft_Portfolio_AMD3').css('display', 'block');
    jQuery('.TS_Port_AM_Table_Div').css('display', 'block');
    jQuery('.Total_Soft_AMImageTable').css('display', 'table');
    jQuery('.Total_Soft_AMImageTable1').css('display', 'table');
    jQuery('.Total_Soft_AMShortTable').css('display', 'table');
  }, 500)
  setTimeout(function () {
    jQuery('.Total_Soft_Portfolio_AMD3').animate({ 'opacity': 1 }, 500);
    jQuery('.TS_Port_AM_Table_Div').animate({ 'opacity': 1 }, 500);
    jQuery('.Total_Soft_AMImageTable').animate({ 'opacity': 1 }, 500);
    jQuery('.Total_Soft_AMImageTable1').animate({ 'opacity': 1 }, 500);
    jQuery('.Total_Soft_AMShortTable').animate({ 'opacity': 1 }, 500);
    jQuery('#totalsoft_delete_choose').css('display', 'none');
    jQuery('input[name=totalsoft_delete_all]').css('display', 'none');
  }, 600)
}
function Copy_Shortcode_Port(IDSHORT) {
  var aux = document.createElement("input");
  var code = document.getElementById(IDSHORT).innerHTML;
  code = code.replace("&lt;", "<");
  code = code.replace("&gt;", ">");
  code = code.replace("&#039;", "'");
  code = code.replace("&#039;", "'");
  aux.setAttribute("value", code);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
}
function TS_Port_Add_Image_Button() {
  jQuery('.TS_Port_Add_Image_Fixed_div').css({
    'transform': 'scale(1)',
    '-moz-transform': 'scale(1)',
    '-webkit-transform': 'scale(1)'
  });
  jQuery('.TS_Port_Add_Image_Absolute_div').css({
    'transform': 'translateY(-50%) scale(1)',
    '-moz-transform': 'translateY(-50%) scale(1)',
    '-webkit-transform': 'translateY(-50%) scale(1)'
  });
  var countAlbum = document.querySelector("#TotalSoftPortfolio_AlbumCount").value;
  var albums = document.querySelectorAll(".TotalSoftHiddenRows td input");
  var html = ""
  for (var i = 0; i < countAlbum; i++) {
    html += "<option value='" + (i + 1) + "' class='TotalSoftPortfolio_ImAlbum TotalSoftPortfolio_ImAlbum_Show' id='TotalSoftPortfolio_ImAlbum_" + (i + 1) + "' >" + albums[i].value + "</option>"
  }
  document.querySelector("#TotalSoftPortfolio_ImAlbum").innerHTML = html;
}
function TS_Port_Add_Image_Button_Close() {
  jQuery('.TS_Port_Add_Image_Fixed_div').css({
    'transform': 'scale(0)',
    '-moz-transform': 'scale(0)',
    '-webkit-transform': 'scale(0)'
  });
  jQuery('.TS_Port_Add_Image_Absolute_div').css({
    'transform': 'translateY(-50%) scale(0)',
    '-moz-transform': 'translateY(-50%) scale(0)',
    '-webkit-transform': 'translateY(-50%) scale(0)'
  });
  Total_Soft_Portfolio_Img_Res();
}
function TotalSoft_Reload() {
  location.reload();
}
function Total_Soft_Portfolio_Editor() {
  tinymce.init({
    selector: '#TotalSoftPortfolio_ImDesc',
    menubar: false,
    statusbar: false,
    height: 170,
    plugins: [
      'advlist autolink lists link image charmap print preview hr',
      'searchreplace wordcount code media ',
      'insertdatetime save table contextmenu directionality',
      'paste textcolor colorpicker textpattern imagetools codesample'
    ],
    toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr | subscript superscript | charmap | print | codesample ",
    fontsize_formats: '8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px',
    font_formats: 'Abadi MT Condensed Light = Abadi MT Condensed Light; ABeeZee = ABeeZee, sans-serif; Abel = Abel, sans-serif; Abhaya Libre = Abhaya Libre, serif; Abril Fatface = Abril Fatface, cursive; Aclonica = Aclonica, sans-serif; Acme = Acme, sans-serif; Actor = Actor, sans-serif; Adamina = Adamina, serif; Advent Pro = Advent Pro, sans-serif; Aguafina Script = Aguafina Script, cursive; Aharoni = Aharoni; Akronim = Akronim, cursive; Aladin = Aladin, cursive; Aldhabi = Aldhabi; Aldrich = Aldrich, sans-serif; Alef = Alef, sans-serif; Alegreya = Alegreya, serif; Alegreya Sans = Alegreya Sans, sans-serif; Alegreya Sans SC = Alegreya Sans SC, sans-serif; Alegreya SC = Alegreya SC, serif; Alex Brush = Alex Brush, cursive; Alfa Slab One = Alfa Slab One, cursive; Alice = Alice, serif; Alike = Alike, serif; Alike Angular = Alike Angular, serif; Allan = Allan, cursive; Allerta = Allerta, sans-serif; Allerta Stencil = Allerta Stencil, sans-serif; Allura = Allura, cursive; Almendra = Almendra, serif; Almendra Display = Almendra Display, cursive; Almendra SC = Almendra SC, serif; Amarante = Amarante, cursive; Amaranth = Amaranth, sans-serif; Amatic SC = Amatic SC, cursive; Amethysta = Amethysta, serif; Amiko = Amiko, sans-serif; Amiri = Amiri, serif; Amita = Amita, cursive; Anaheim = Anaheim, sans-serif; Andada = Andada, serif; Andalus = Andalus; Andika = Andika, sans-serif; Angkor = Angkor, cursive; Angsana New = Angsana New; AngsanaUPC = AngsanaUPC; Annie Use Your Telescope = Annie Use Your Telescope, cursive; Anonymous Pro = Anonymous Pro, monospace; Antic = Antic, sans-serif; Antic Didone = Antic Didone, serif; Antic Slab = Antic Slab, serif; Anton = Anton, sans-serif; Aparajita = Aparajita; Arabic Typesetting = Arabic Typesetting; Arapey = Arapey, serif; Arbutus = Arbutus, cursive; Arbutus Slab = Arbutus Slab, serif; Architects Daughter = Architects Daughter, cursive; Archivo = Archivo, sans-serif; Archivo Black = Archivo Black, sans-serif; Archivo Narrow = Archivo Narrow, sans-serif; Aref Ruqaa = Aref Ruqaa, serif; Arial = Arial; Arial Black = Arial Black; Arimo = Arimo, sans-serif; Arima Madurai = Arima Madurai, cursive; Arizonia = Arizonia, cursive; Armata = Armata, sans-serif; Arsenal = Arsenal, sans-serif; Artifika = Artifika, serif; Arvo = Arvo, serif; Arya = Arya, sans-serif; Asap = Asap, sans-serif; Asap Condensed = Asap Condensed, sans-serif; Asar = Asar, serif; Asset = Asset, cursive; Assistant = Assistant, sans-serif; Astloch = Astloch, cursive; Asul = Asul, sans-serif; Athiti = Athiti, sans-serif; Atma = Atma, cursive; Atomic Age = Atomic Age, cursive; Aubrey = Aubrey, cursive; Audiowide = Audiowide, cursive; Autour One = Autour One, cursive; Average = Average, serif; Average Sans = Average Sans, sans-serif; Averia Gruesa Libre = Averia Gruesa Libre, cursive; Averia Libre = Averia Libre, cursive; Averia Sans Libre = Averia Sans Libre, cursive; Averia Serif Libre = Averia Serif Libre, cursive; Bad Script = Bad Script, cursive; Bahiana = Bahiana, cursive; Baloo = Baloo, cursive; Balthazar = Balthazar, serif; Bangers = Bangers, cursive; Barlow = Barlow, sans-serif; Barlow Condensed = Barlow Condensed, sans-serif; Barlow Semi Condensed = Barlow Semi Condensed, sans-serif; Barrio = Barrio, cursive; Basic = Basic, sans-serif; Batang = Batang; BatangChe = BatangChe; Battambang = Battambang, cursive; Baumans = Baumans, cursive; Bayon = Bayon, cursive; Belgrano = Belgrano, serif; Bellefair = Bellefair, serif; Belleza = Belleza, sans-serif; BenchNine = BenchNine, sans-serif; Bentham = Bentham, serif; Berkshire Swash = Berkshire Swash, cursive; Bevan = Bevan, cursive; Bigelow Rules = Bigelow Rules, cursive; Bigshot One = Bigshot One, cursive; Bilbo = Bilbo, cursive; Bilbo Swash Caps = Bilbo Swash Caps, cursive; BioRhyme = BioRhyme, serif; BioRhyme Expanded = BioRhyme Expanded, serif; Biryani = Biryani, sans-serif; Bitter = Bitter, serif; Black And White Picture = Black And White Picture, sans-serif; Black Han Sans = Black Han Sans, sans-serif; Black Ops One = Black Ops One, cursive; Bokor = Bokor, cursive; Bonbon = Bonbon, cursive; Boogaloo = Boogaloo, cursive; Bowlby One = Bowlby One, cursive; Bowlby One SC = Bowlby One SC, cursive; Brawler = Brawler, serif; Bree Serif = Bree Serif, serif; Browallia New = Browallia New; BrowalliaUPC = BrowalliaUPC; Bubbler One = Bubbler One, sans-serif; Bubblegum Sans = Bubblegum Sans, cursive; Buda = Buda, cursive; Buenard = Buenard, serif; Bungee = Bungee, cursive; Bungee Hairline = Bungee Hairline, cursive; Bungee Inline = Bungee Inline, cursive; Bungee Outline = Bungee Outline, cursive; Bungee Shade = Bungee Shade, cursive; Butcherman = Butcherman, cursive; Butterfly Kids = Butterfly Kids, cursive; Cabin = Cabin, sans-serif; Cabin Condensed = Cabin Condensed, sans-serif; Cabin Sketch = Cabin Sketch, cursive; Caesar Dressing = Caesar Dressing, cursive; Cagliostro = Cagliostro, sans-serif; Cairo = Cairo, sans-serif; Calibri = Calibri; Calibri Light = Calibri Light; Calisto MT = Calisto MT; Calligraffitti = Calligraffitti, cursive; Cambay = Cambay, sans-serif; Cambo = Cambo, serif; Cambria = Cambria; Candal = Candal, sans-serif; Candara = Candara; Cantarell = Cantarell, sans-serif; Cantata One = Cantata One, serif; Cantora One = Cantora One, sans-serif; Capriola = Capriola, sans-serif; Cardo = Cardo, serif; Carme = Carme, sans-serif; Carrois Gothic = Carrois Gothic, sans-serif; Carrois Gothic SC = Carrois Gothic SC, sans-serif; Carter One = Carter One, cursive; Catamaran = Catamaran, sans-serif; Caudex = Caudex, serif; Caveat = Caveat, cursive; Caveat Brush = Caveat Brush, cursive; Cedarville Cursive = Cedarville Cursive, cursive; Century Gothic = Century Gothic; Ceviche One = Ceviche One, cursive; Changa = Changa, sans-serif; Changa One = Changa One, cursive; Chango = Chango, cursive; Chathura = Chathura, sans-serif; Chau Philomene One = Chau Philomene One, sans-serif; Chela One = Chela One, cursive; Chelsea Market = Chelsea Market, cursive; Chenla = Chenla, cursive; Cherry Cream Soda = Cherry Cream Soda, cursive; Cherry Swash = Cherry Swash, cursive; Chewy = Chewy, cursive; Chicle = Chicle, cursive; Chivo = Chivo, sans-serif; Chonburi = Chonburi, cursive; Cinzel = Cinzel, serif; Cinzel Decorative = Cinzel Decorative, cursive; Clicker Script = Clicker Script, cursive; Coda = Coda, cursive; Coda Caption = Coda Caption, sans-serif; Codystar = Codystar, cursive; Coiny = Coiny, cursive; Combo = Combo, cursive; Comic Sans MS = Comic Sans MS; Coming Soon = Coming Soon, cursive; Comfortaa = Comfortaa, cursive; Concert One = Concert One, cursive; Condiment = Condiment, cursive; Consolas = Consolas; Constantia = Constantia; Content = Content, cursive; Contrail One = Contrail One, cursive; Convergence = Convergence, sans-serif; Cookie = Cookie, cursive; Copperplate Gothic = Copperplate Gothic; Copperplate Gothic Light = Copperplate Gothic Light; Copse = Copse, serif; Corbel = Corbel; Corben = Corben, cursive; Cordia New = Cordia New; CordiaUPC = CordiaUPC; Cormorant = Cormorant, serif; Cormorant Garamond = Cormorant Garamond, serif; Cormorant Infant = Cormorant Infant, serif; Cormorant SC = Cormorant SC, serif; Cormorant Unicase = Cormorant Unicase, serif; Cormorant Upright = Cormorant Upright, serif; Courgette = Courgette, cursive; Courier New = Courier New; Cousine = Cousine, monospace; Coustard = Coustard, serif; Covered By Your Grace = Covered By Your Grace, cursive; Crafty Girls = Crafty Girls, cursive; Creepster = Creepster, cursive; Crete Round = Crete Round, serif; Crimson Text = Crimson Text, serif; Croissant One = Croissant One, cursive; Crushed = Crushed, cursive; Cuprum = Cuprum, sans-serif; Cute Font = Cute Font, cursive; Cutive = Cutive, serif; Cutive Mono = Cutive Mono, monospace; Damion = Damion, cursive; Dancing Script = Dancing Script, cursive; Dangrek = Dangrek, cursive; DaunPenh = DaunPenh; David = David; David Libre = David Libre, serif; Dawning of a New Day = Dawning of a New Day, cursive; Days One = Days One, sans-serif; Delius = Delius, cursive; Delius Swash Caps = Delius Swash Caps, cursive; Delius Unicase = Delius Unicase, cursive; Della Respira = Della Respira, serif; Denk One = Denk One, sans-serif; Devonshire = Devonshire, cursive; DFKai-SB = DFKai-SB; Dhurjati = Dhurjati, sans-serif; Didact Gothic = Didact Gothic, sans-serif; DilleniaUPC = DilleniaUPC; Diplomata = Diplomata, cursive; Diplomata SC = Diplomata SC, cursive; Do Hyeon = Do Hyeon, sans-serif; DokChampa = DokChampa; Dokdo = Dokdo, cursive; Domine = Domine, serif; Donegal One = Donegal One, serif; Doppio One = Doppio One, sans-serif; Dorsa = Dorsa, sans-serif; Dosis = Dosis, sans-serif; Dotum = Dotum; DotumChe = DotumChe; Dr Sugiyama = Dr Sugiyama, cursive; Duru Sans = Duru Sans, sans-serif; Dynalight = Dynalight, cursive; Eagle Lake = Eagle Lake, cursive; East Sea Dokdo = East Sea Dokdo, cursive; Eater = Eater, cursive; EB Garamond = EB Garamond, serif; Ebrima = Ebrima; Economica = Economica, sans-serif; Eczar = Eczar, serif; El Messiri = El Messiri, sans-serif; Electrolize = Electrolize, sans-serif; Elsie = Elsie, cursive; Elsie Swash Caps = Elsie Swash Caps, cursive; Emblema One = Emblema One, cursive; Emilys Candy = Emilys Candy, cursive; Encode Sans = Encode Sans, sans-serif; Encode Sans Condensed = Encode Sans Condensed, sans-serif; Encode Sans Expanded = Encode Sans Expanded, sans-serif; Encode Sans Semi Condensed = Encode Sans Semi Condensed, sans-serif; Encode Sans Semi Expanded = Encode Sans Semi Expanded, sans-serif; Engagement = Engagement, cursive; Englebert = Englebert, sans-serif; Enriqueta = Enriqueta, serif; Erica One = Erica One, cursive; Esteban = Esteban, serif; Estrangelo Edessa = Estrangelo Edessa; EucrosiaUPC = EucrosiaUPC; Euphemia = Euphemia; Euphoria Script = Euphoria Script, cursive; Ewert = Ewert, cursive; Exo = Exo, sans-serif; Expletus Sans = Expletus Sans, cursive; FangSong = FangSong; Fanwood Text = Fanwood Text, serif; Farsan = Farsan, cursive; Fascinate = Fascinate, cursive; Fascinate Inline = Fascinate Inline, cursive; Faster One = Faster One, cursive; Fasthand = Fasthand, serif; Fauna One = Fauna One, serif; Faustina = Faustina, serif; Federant = Federant, cursive; Federo = Federo, sans-serif; Felipa = Felipa, cursive; Fenix = Fenix, serif; Finger Paint = Finger Paint, cursive; Fira Mono = Fira Mono, monospace; Fira Sans = Fira Sans, sans-serif; Fira Sans Condensed = Fira Sans Condensed, sans-serif; Fira Sans Extra Condensed = Fira Sans Extra Condensed, sans-serif; Fjalla One = Fjalla One, sans-serif; Fjord One = Fjord One, serif; Flamenco = Flamenco, cursive; Flavors = Flavors, cursive; Fondamento = Fondamento, cursive; Fontdiner Swanky = Fontdiner Swanky, cursive; Forum = Forum, cursive; Francois One = Francois One, sans-serif; Frank Ruhl Libre = Frank Ruhl Libre, serif; Franklin Gothic Medium = Franklin Gothic Medium; FrankRuehl = FrankRuehl; Freckle Face = Freckle Face, cursive; Fredericka the Great = Fredericka the Great, cursive; Fredoka One = Fredoka One, cursive; Freehand = Freehand, cursive; FreesiaUPC = FreesiaUPC; Fresca = Fresca, sans-serif; Frijole = Frijole, cursive; Fruktur = Fruktur, cursive; Fugaz One = Fugaz One, cursive; Gabriela = Gabriela, serif; Gabriola = Gabriola; Gadugi = Gadugi; Gaegu = Gaegu, cursive; Gafata = Gafata, sans-serif; Galada = Galada, cursive; Galdeano = Galdeano, sans-serif; Galindo = Galindo, cursive; Gamja Flower = Gamja Flower, cursive; Gautami = Gautami; Gentium Basic = Gentium Basic, serif; Gentium Book Basic = Gentium Book Basic, serif; Geo = Geo, sans-serif; Georgia = Georgia; Geostar = Geostar, cursive; Geostar Fill = Geostar Fill, cursive; Germania One = Germania One, cursive; GFS Didot = GFS Didot, serif; GFS Neohellenic = GFS Neohellenic, sans-serif; Gidugu = Gidugu, sans-serif; Gilda Display = Gilda Display, serif; Gisha = Gisha; Give You Glory = Give You Glory, cursive; Glass Antiqua = Glass Antiqua, cursive; Glegoo = Glegoo, serif; Gloria Hallelujah = Gloria Hallelujah, cursive; Goblin One = Goblin One, cursive; Gochi Hand = Gochi Hand, cursive; Gorditas = Gorditas, cursive; Gothic A1 = Gothic A1, sans-serif; Graduate = Graduate, cursive; Grand Hotel = Grand Hotel, cursive; Gravitas One = Gravitas One, cursive; Great Vibes = Great Vibes, cursive; Griffy = Griffy, cursive; Gruppo = Gruppo, cursive; Gudea = Gudea, sans-serif; Gugi = Gugi, cursive; Gulim = Gulim; GulimChe = GulimChe; Gungsuh = Gungsuh; GungsuhChe = GungsuhChe; Gurajada = Gurajada, serif; Habibi = Habibi, serif; Halant = Halant, serif; Hammersmith One = Hammersmith One, sans-serif; Hanalei = Hanalei, cursive; Hanalei Fill = Hanalei Fill, cursive; Handlee = Handlee, cursive; Hanuman = Hanuman, serif; Happy Monkey = Happy Monkey, cursive; Harmattan = Harmattan, sans-serif; Headland One = Headland One, serif; Heebo = Heebo, sans-serif; Henny Penny = Henny Penny, cursive; Herr Von Muellerhoff = Herr Von Muellerhoff, cursive; Hi Melody = Hi Melody, cursive; Hind = Hind, sans-serif; Holtwood One SC = Holtwood One SC, serif; Homemade Apple = Homemade Apple, cursive; Homenaje = Homenaje, sans-serif; IBM Plex Mono = IBM Plex Mono, monospace; IBM Plex Sans = IBM Plex Sans, sans-serif; IBM Plex Sans Condensed = IBM Plex Sans Condensed, sans-serif; IBM Plex Serif = IBM Plex Serif, serif; Iceberg = Iceberg, cursive; Iceland = Iceland, cursive; IM Fell Double Pica = IM Fell Double Pica, serif; IM Fell Double Pica SC = IM Fell Double Pica SC, serif; IM Fell DW Pica = IM Fell DW Pica, serif; IM Fell DW Pica SC = IM Fell DW Pica SC, serif; IM Fell English = IM Fell English, serif; IM Fell English SC = IM Fell English SC, serif; IM Fell French Canon = IM Fell French Canon, serif; IM Fell French Canon SC = IM Fell French Canon SC, serif; IM Fell Great Primer = IM Fell Great Primer, serif; IM Fell Great Primer SC = IM Fell Great Primer SC, serif; Impact = Impact; Imprima = Imprima, sans-serif; Inconsolata = Inconsolata, monospace; Inder = Inder, sans-serif; Indie Flower = Indie Flower, cursive; Inika = Inika, serif; Irish Grover = Irish Grover, cursive; IrisUPC = IrisUPC; Istok Web = Istok Web, sans-serif; Iskoola Pota = Iskoola Pota; Italiana = Italiana, serif; Italianno = Italianno, cursive; Itim = Itim, cursive; Jacques Francois = Jacques Francois, serif; Jacques Francois Shadow = Jacques Francois Shadow, cursive; Jaldi = Jaldi, sans-serif; JasmineUPC = JasmineUPC; Jim Nightshade = Jim Nightshade, cursive; Jockey One = Jockey One, sans-serif; Jolly Lodger = Jolly Lodger, cursive; Jomhuria = Jomhuria, cursive; Josefin Sans = Josefin Sans, sans-serif; Josefin Slab = Josefin Slab, serif; Joti One = Joti One, cursive; Jua = Jua, sans-serif; Judson = Judson, serif; Julee = Julee, cursive; Julius Sans One = Julius Sans One, sans-serif; Junge = Junge, serif; Jura = Jura, sans-serif; Just Another Hand = Just Another Hand, cursive; Just Me Again Down Here = Just Me Again Down Here, cursive; Kadwa = Kadwa, serif; KaiTi = KaiTi; Kalam = Kalam, cursive; Kalinga = Kalinga; Kameron = Kameron, serif; Kanit = Kanit, sans-serif; Kantumruy = Kantumruy, sans-serif; Karla = Karla, sans-serif; Karma = Karma, serif; Kartika = Kartika; Katibeh = Katibeh, cursive; Kaushan Script = Kaushan Script, cursive; Kavivanar = Kavivanar, cursive; Kavoon = Kavoon, cursive; Kdam Thmor = Kdam Thmor, cursive; Keania One = Keania One, cursive; Kelly Slab = Kelly Slab, cursive; Kenia = Kenia, cursive; Khand = Khand, sans-serif; Khmer = Khmer, cursive; Khmer UI = Khmer UI; Khula = Khula, sans-serif; Kirang Haerang = Kirang Haerang, cursive; Kite One = Kite One, sans-serif; Knewave = Knewave, cursive; KodchiangUPC = KodchiangUPC; Kokila = Kokila; Kotta One = Kotta One, serif; Koulen = Koulen, cursive; Kranky = Kranky, cursive; Kreon = Kreon, serif; Kristi = Kristi, cursive; Krona One = Krona One, sans-serif; Kurale = Kurale, serif; La Belle Aurore = La Belle Aurore, cursive; Laila = Laila, serif; Lakki Reddy = Lakki Reddy, cursive; Lalezar = Lalezar, cursive; Lancelot = Lancelot, cursive; Lao UI = Lao UI; Lateef = Lateef, cursive; Latha = Latha; Lato = Lato, sans-serif; League Script = League Script, cursive; Leckerli One = Leckerli One, cursive; Ledger = Ledger, serif; Leelawadee = Leelawadee; Lekton = Lekton, sans-serif; Lemon = Lemon, cursive; Lemonada = Lemonada, cursive; Levenim MT = Levenim MT; Libre Baskerville = Libre Baskerville, serif; Libre Franklin = Libre Franklin, sans-serif; Life Savers = Life Savers, cursive; Lilita One = Lilita One, cursive; Lily Script One = Lily Script One, cursive; LilyUPC = LilyUPC; Limelight = Limelight, cursive; Linden Hill = Linden Hill, serif; Lobster = Lobster, cursive; Lobster Two = Lobster Two, cursive; Londrina Outline = Londrina Outline, cursive; Londrina Shadow = Londrina Shadow, cursive; Londrina Sketch = Londrina Sketch, cursive; Londrina Solid = Londrina Solid, cursive; Lora = Lora, serif; Love Ya Like A Sister = Love Ya Like A Sister, cursive; Loved by the King = Loved by the King, cursive; Lovers Quarrel = Lovers Quarrel, cursive; Lucida Console = Lucida Console; Lucida Handwriting Italic = Lucida Handwriting Italic; Lucida Sans Unicode = Lucida Sans Unicode; Luckiest Guy = Luckiest Guy, cursive; Lusitana = Lusitana, serif; Lustria = Lustria, serif; Macondo = Macondo, cursive; Macondo Swash Caps = Macondo Swash Caps, cursive; Mada = Mada, sans-serif; Magra = Magra, sans-serif; Maiden Orange = Maiden Orange, cursive; Maitree = Maitree, serif; Mako = Mako, sans-serif; Malgun Gothic = Malgun Gothic; Mallanna = Mallanna, sans-serif; Mandali = Mandali, sans-serif; Mangal = Mangal; Manny ITC = Manny ITC; Manuale = Manuale, serif; Marcellus = Marcellus, serif; Marcellus SC = Marcellus SC, serif; Marck Script = Marck Script, cursive; Margarine = Margarine, cursive; Marko One = Marko One, serif; Marlett = Marlett; Marmelad = Marmelad, sans-serif; Martel = Martel, serif; Martel Sans = Martel Sans, sans-serif; Marvel = Marvel, sans-serif; Mate = Mate, serif; Mate SC = Mate SC, serif; Maven Pro = Maven Pro, sans-serif; McLaren = McLaren, cursive; Meddon = Meddon, cursive; MedievalSharp = MedievalSharp, cursive; Medula One = Medula One, cursive; Meera Inimai = Meera Inimai, sans-serif; Megrim = Megrim, cursive; Meie Script = Meie Script, cursive; Meiryo = Meiryo; Meiryo UI = Meiryo UI; Merienda = Merienda, cursive; Merienda One = Merienda One, cursive; Merriweather = Merriweather, serif; Merriweather Sans = Merriweather Sans, sans-serif; Metal = Metal, cursive; Metal Mania = Metal Mania, cursive; Metamorphous = Metamorphous, cursive; Metrophobic = Metrophobic, sans-serif; Michroma = Michroma, sans-serif; Microsoft Himalaya = Microsoft Himalaya; Microsoft JhengHei = Microsoft JhengHei; Microsoft JhengHei UI = Microsoft JhengHei UI; Microsoft New Tai Lue = Microsoft New Tai Lue; Microsoft PhagsPa = Microsoft PhagsPa; Microsoft Sans Serif = Microsoft Sans Serif; Microsoft Tai Le = Microsoft Tai Le; Microsoft Uighur = Microsoft Uighur; Microsoft YaHei = Microsoft YaHei; Microsoft YaHei UI = Microsoft YaHei UI; Microsoft Yi Baiti = Microsoft Yi Baiti; Milonga = Milonga, cursive; Miltonian = Miltonian, cursive; Miltonian Tattoo = Miltonian Tattoo, cursive; Mina = Mina, sans-serif; MingLiU_HKSCS = MingLiU_HKSCS; MingLiU_HKSCS-ExtB = MingLiU_HKSCS-ExtB; Miniver = Miniver, cursive; Miriam = Miriam; Miriam Libre = Miriam Libre, sans-serif; Mirza = Mirza, cursive; Miss Fajardose = Miss Fajardose, cursive; Mitr = Mitr, sans-serif; Modak = Modak, cursive; Modern Antiqua = Modern Antiqua, cursive; Mogra = Mogra, cursive; Molengo = Molengo, sans-serif; Molle = Molle, cursive; Monda = Monda, sans-serif; Mongolian Baiti = Mongolian Baiti; Monofett = Monofett, cursive; Monoton = Monoton, cursive; Monsieur La Doulaise = Monsieur La Doulaise, cursive; Montaga = Montaga, serif; Montez = Montez, cursive; Montserrat = Montserrat, sans-serif; Montserrat Alternates = Montserrat Alternates, sans-serif; Montserrat Subrayada = Montserrat Subrayada, sans-serif; MoolBoran = MoolBoran; Moul = Moul, cursive; Moulpali = Moulpali, cursive; Mountains of Christmas = Mountains of Christmas, cursive; Mouse Memoirs = Mouse Memoirs, sans-serif; Mr Bedfort = Mr Bedfort, cursive; Mr Dafoe = Mr Dafoe, cursive; Mr De Haviland = Mr De Haviland, cursive; Mrs Saint Delafield = Mrs Saint Delafield, cursive; Mrs Sheppards = Mrs Sheppards, cursive; MS UI Gothic = MS UI Gothic; Mukta = Mukta, sans-serif; Muli = Muli, sans-serif; MV Boli = MV Boli; Myanmar Text = Myanmar Text; Mystery Quest = Mystery Quest, cursive; Nanum Brush Script = Nanum Brush Script, cursive; Nanum Gothic = Nanum Gothic, sans-serif; Nanum Gothic Coding = Nanum Gothic Coding, monospace; Nanum Myeongjo = Nanum Myeongjo, serif; Nanum Pen Script = Nanum Pen Script, cursive; Narkisim = Narkisim; Neucha = Neucha, cursive; Neuton = Neuton, serif; New Rocker = New Rocker, cursive; News Cycle = News Cycle, sans-serif; News Gothic MT = News Gothic MT; Niconne = Niconne, cursive; Nirmala UI = Nirmala UI; Nixie One = Nixie One, cursive; Nobile = Nobile, sans-serif; Nokora = Nokora, serif; Norican = Norican, cursive; Nosifer = Nosifer, cursive; Nothing You Could Do = Nothing You Could Do, cursive; Noticia Text = Noticia Text, serif; Noto Sans = Noto Sans, sans-serif; Noto Serif = Noto Serif, serif; Nova Cut = Nova Cut, cursive; Nova Flat = Nova Flat, cursive; Nova Mono = Nova Mono, monospace; Nova Oval = Nova Oval, cursive; Nova Round = Nova Round, cursive; Nova Script = Nova Script, cursive; Nova Slim = Nova Slim, cursive; Nova Square = Nova Square, cursive; NSimSun = NSimSun; NTR = NTR, sans-serif; Numans = Numans, sans-serif; Nunito = Nunito, sans-serif; Nunito Sans = Nunito Sans, sans-serif; Nyala = Nyala; Odor Mean Chey = Odor Mean Chey, cursive; Offside = Offside, cursive; Old Standard TT = Old Standard TT, serif; Oldenburg = Oldenburg, cursive; Oleo Script = Oleo Script, cursive; Oleo Script Swash Caps = Oleo Script Swash Caps, cursive; Open Sans = Open Sans, sans-serif; Open Sans Condensed = Open Sans Condensed, sans-serif; Oranienbaum = Oranienbaum, serif; Orbitron = Orbitron, sans-serif; Oregano = Oregano, cursive; Orienta = Orienta, sans-serif; Original Surfer = Original Surfer, cursive; Oswald = Oswald, sans-serif; Over the Rainbow = Over the Rainbow, cursive; Overlock = Overlock, cursive; Overlock SC = Overlock SC, cursive; Overpass = Overpass, sans-serif; Overpass Mono = Overpass Mono, monospace; Ovo = Ovo, serif; Oxygen = Oxygen, sans-serif; Oxygen Mono = Oxygen Mono, monospace; Pacifico = Pacifico, cursive; Padauk = Padauk, sans-serif; Palanquin = Palanquin, sans-serif; Palanquin Dark = Palanquin Dark, sans-serif; Palatino Linotype = Palatino Linotype; Pangolin = Pangolin, cursive; Paprika = Paprika, cursive; Parisienne = Parisienne, cursive; Passero One = Passero One, cursive; Passion One = Passion One, cursive; Pathway Gothic One = Pathway Gothic One, sans-serif; Patrick Hand = Patrick Hand, cursive; Patrick Hand SC = Patrick Hand SC, cursive; Pattaya = Pattaya, sans-serif; Patua One = Patua One, cursive; Pavanam = Pavanam, sans-serif; Paytone One = Paytone One, sans-serif; Peddana = Peddana, serif; Peralta = Peralta, cursive; Permanent Marker = Permanent Marker, cursive; Petit Formal Script = Petit Formal Script, cursive; Petrona = Petrona, serif; Philosopher = Philosopher, sans-serif; Piedra = Piedra, cursive; Pinyon Script = Pinyon Script, cursive; Pirata One = Pirata One, cursive; Plantagenet Cherokee = Plantagenet Cherokee; Plaster = Plaster, cursive; Play = Play, sans-serif; Playball = Playball, cursive; Playfair Display = Playfair Display, serif; Playfair Display SC = Playfair Display SC, serif; Podkova = Podkova, serif; Poiret One = Poiret One, cursive; Poller One = Poller One, cursive; Poly = Poly, serif; Pompiere = Pompiere, cursive; Pontano Sans = Pontano Sans, sans-serif; Poor Story = Poor Story, cursive; Poppins = Poppins, sans-serif; Port Lligat Sans = Port Lligat Sans, sans-serif; Port Lligat Slab = Port Lligat Slab, serif; Pragati Narrow = Pragati Narrow, sans-serif; Prata = Prata, serif; Preahvihear = Preahvihear, cursive; Pridi = Pridi, serif; Princess Sofia = Princess Sofia, cursive; Prociono = Prociono, serif; Prompt = Prompt, sans-serif; Prosto One = Prosto One, cursive; Proza Libre = Proza Libre, sans-serif; PT Mono = PT Mono, monospace; PT Sans = PT Sans, sans-serif; PT Sans Caption = PT Sans Caption, sans-serif; PT Sans Narrow = PT Sans Narrow, sans-serif; PT Serif = PT Serif, serif; PT Serif Caption = PT Serif Caption, serif; Puritan = Puritan, sans-serif; Purple Purse = Purple Purse, cursive; Quando = Quando, serif; Quantico = Quantico, sans-serif; Quattrocento = Quattrocento, serif; Quattrocento Sans = Quattrocento Sans, sans-serif; Questrial = Questrial, sans-serif; Quicksand = Quicksand, sans-serif; Quintessential = Quintessential, cursive; Qwigley = Qwigley, cursive; Raavi = Raavi; Racing Sans One = Racing Sans One, cursive; Radley = Radley, serif; Rajdhani = Rajdhani, sans-serif; Rakkas = Rakkas, cursive; Raleway = Raleway, sans-serif; Raleway Dots = Raleway Dots, cursive; Ramabhadra = Ramabhadra, sans-serif; Ramaraja = Ramaraja, serif; Rambla = Rambla, sans-serif; Rammetto One = Rammetto One, cursive; Ranchers = Ranchers, cursive; Rancho = Rancho, cursive; Ranga = Ranga, cursive; Rasa = Rasa, serif; Rationale = Rationale, sans-serif; Ravi Prakash = Ravi Prakash, cursive; Redressed = Redressed, cursive; Reem Kufi = Reem Kufi, sans-serif; Reenie Beanie = Reenie Beanie, cursive; Revalia = Revalia, cursive; Rhodium Libre = Rhodium Libre, serif; Ribeye = Ribeye, cursive; Ribeye Marrow = Ribeye Marrow, cursive; Righteous = Righteous, cursive; Risque = Risque, cursive; Roboto = Roboto, sans-serif; Roboto Condensed = Roboto Condensed, sans-serif; Roboto Mono = Roboto Mono, monospace; Roboto Slab = Roboto Slab, serif; Rochester = Rochester, cursive; Rock Salt = Rock Salt, cursive; Rod = Rod; Rokkitt = Rokkitt, serif; Romanesco = Romanesco, cursive; Ropa Sans = Ropa Sans, sans-serif; Rosario = Rosario, sans-serif; Rosarivo = Rosarivo, serif; Rouge Script = Rouge Script, cursive; Rozha One = Rozha One, serif; Rubik = Rubik, sans-serif; Rubik Mono One = Rubik Mono One, sans-serif; Ruda = Ruda, sans-serif; Rufina = Rufina, serif; Ruge Boogie = Ruge Boogie, cursive; Ruluko = Ruluko, sans-serif; Rum Raisin = Rum Raisin, sans-serif; Ruslan Display = Ruslan Display, cursive; Russo One = Russo One, sans-serif; Ruthie = Ruthie, cursive; Rye = Rye, cursive; Sacramento = Sacramento, cursive; Sahitya = Sahitya, serif; Sail = Sail, cursive; Saira = Saira, sans-serif; Saira Condensed = Saira Condensed, sans-serif; Saira Extra Condensed = Saira Extra Condensed, sans-serif; Saira Semi Condensed = Saira Semi Condensed, sans-serif; Sakkal Majalla = Sakkal Majalla; Salsa = Salsa, cursive; Sanchez = Sanchez, serif; Sancreek = Sancreek, cursive; Sansita = Sansita, sans-serif; Sarala = Sarala, sans-serif; Sarina = Sarina, cursive; Sarpanch = Sarpanch, sans-serif; Satisfy = Satisfy, cursive; Scada = Scada, sans-serif; Scheherazade = Scheherazade, serif; Schoolbell = Schoolbell, cursive; Scope One = Scope One, serif; Seaweed Script = Seaweed Script, cursive; Secular One = Secular One, sans-serif; Sedgwick Ave = Sedgwick Ave, cursive; Sedgwick Ave Display = Sedgwick Ave Display, cursive; Segoe Print = Segoe Print; Segoe Script = Segoe Script; Segoe UI Symbol = Segoe UI Symbol; Sevillana = Sevillana, cursive; Seymour One = Seymour One, sans-serif; Shadows Into Light = Shadows Into Light, cursive; Shadows Into Light Two = Shadows Into Light Two, cursive; Shanti = Shanti, sans-serif; Share = Share, cursive; Share Tech = Share Tech, sans-serif; Share Tech Mono = Share Tech Mono, monospace; Shojumaru = Shojumaru, cursive; Shonar Bangla = Shonar Bangla; Short Stack = Short Stack, cursive; Shrikhand = Shrikhand, cursive; Shruti = Shruti; Siemreap = Siemreap, cursive; Sigmar One = Sigmar One, cursive; Signika = Signika, sans-serif; Signika Negative = Signika Negative, sans-serif; SimHei = SimHei; SimKai = SimKai; Simonetta = Simonetta, cursive; Simplified Arabic = Simplified Arabic; SimSun = SimSun; SimSun-ExtB = SimSun-ExtB; Sintony = Sintony, sans-serif; Sirin Stencil = Sirin Stencil, cursive; Six Caps = Six Caps, sans-serif; Skranji = Skranji, cursive; Slackey = Slackey, cursive; Smokum = Smokum, cursive; Smythe = Smythe, cursive; Sniglet = Sniglet, cursive; Snippet = Snippet, sans-serif; Snowburst One = Snowburst One, cursive; Sofadi One = Sofadi One, cursive; Sofia = Sofia, cursive; Song Myung = Song Myung, serif; Sonsie One = Sonsie One, cursive; Sorts Mill Goudy = Sorts Mill Goudy, serif; Source Code Pro = Source Code Pro, monospace; Source Sans Pro = Source Sans Pro, sans-serif; Source Serif Pro = Source Serif Pro, serif; Space Mono = Space Mono, monospace; Special Elite = Special Elite, cursive; Spectral = Spectral, serif; Spectral SC = Spectral SC, serif; Spicy Rice = Spicy Rice, cursive; Spinnaker = Spinnaker, sans-serif; Spirax = Spirax, cursive; Squada One = Squada One, cursive; Sree Krushnadevaraya = Sree Krushnadevaraya, serif; Sriracha = Sriracha, cursive; Stalemate = Stalemate, cursive; Stalinist One = Stalinist One, cursive; Stardos Stencil = Stardos Stencil, cursive; Stint Ultra Condensed = Stint Ultra Condensed, cursive; Stint Ultra Expanded = Stint Ultra Expanded, cursive; Stoke = Stoke, serif; Strait = Strait, sans-serif; Stylish = Stylish, sans-serif; Sue Ellen Francisco = Sue Ellen Francisco, cursive; Suez One = Suez One, serif; Sumana = Sumana, serif; Sunflower = Sunflower, sans-serif; Sunshiney = Sunshiney, cursive; Supermercado One = Supermercado One, cursive; Sura = Sura, serif; Suranna = Suranna, serif; Suravaram = Suravaram, serif; Suwannaphum = Suwannaphum, cursive; Swanky and Moo Moo = Swanky and Moo Moo, cursive; Sylfaen = Sylfaen; Syncopate = Syncopate, sans-serif; Tahoma = Tahoma; Tajawal = Tajawal, sans-serif; Tangerine = Tangerine, cursive; Taprom = Taprom, cursive; Tauri = Tauri, sans-serif; Taviraj = Taviraj, serif; Teko = Teko, sans-serif; Telex = Telex, sans-serif; Tenali Ramakrishna = Tenali Ramakrishna, sans-serif; Tenor Sans = Tenor Sans, sans-serif; Text Me One = Text Me One, sans-serif; The Girl Next Door = The Girl Next Door, cursive; Tienne = Tienne, serif; Tillana = Tillana, cursive; Times New Roman = Times New Roman; Timmana = Timmana, sans-serif; Tinos = Tinos, serif; Titan One = Titan One, cursive; Titillium Web = Titillium Web, sans-serif; Trade Winds = Trade Winds, cursive; Traditional Arabic = Traditional Arabic; Trebuchet MS = Trebuchet MS; Trirong = Trirong, serif; Trocchi = Trocchi, serif; Trochut = Trochut, cursive; Trykker = Trykker, serif; Tulpen One = Tulpen One, cursive; Tunga = Tunga; Ubuntu = Ubuntu, sans-serif; Ubuntu Condensed = Ubuntu Condensed, sans-serif; Ubuntu Mono = Ubuntu Mono, monospace; Ultra = Ultra, serif; Uncial Antiqua = Uncial Antiqua, cursive; Underdog = Underdog, cursive; Unica One = Unica One, cursive; UnifrakturCook = UnifrakturCook, cursive; UnifrakturMaguntia = UnifrakturMaguntia, cursive; Unkempt = Unkempt, cursive; Unlock = Unlock, cursive; Unna = Unna, serif; Utsaah = Utsaah; Vampiro One = Vampiro One, cursive; Vani = Vani; Varela = Varela, sans-serif; Varela Round = Varela Round, sans-serif; Vast Shadow = Vast Shadow, cursive; Vesper Libre = Vesper Libre, serif; Vibur = Vibur, cursive; Vidaloka = Vidaloka, serif; Viga = Viga, sans-serif; Vijaya = Vijaya; Voces = Voces, cursive; Volkhov = Volkhov, serif; Vollkorn = Vollkorn, serif; Vollkorn SC = Vollkorn SC, serif; Voltaire = Voltaire, sans-serif; VT323 = VT323, monospace; Waiting for the Sunrise = Waiting for the Sunrise, cursive; Wallpoet = Wallpoet, cursive; Walter Turncoat = Walter Turncoat, cursive; Warnes = Warnes, cursive; Wellfleet = Wellfleet, cursive; Wendy One = Wendy One, sans-serif; Wire One = Wire One, sans-serif; Work Sans = Work Sans, sans-serif; Yanone Kaffeesatz = Yanone Kaffeesatz, sans-serif; Yantramanav = Yantramanav, sans-serif; Yatra One = Yatra One, cursive; Yellowtail = Yellowtail, cursive; Yeon Sung = Yeon Sung, cursive; Yeseva One = Yeseva One, cursive; Yesteryear = Yesteryear, cursive; Yrsa = Yrsa, serif; Zeyada = Zeyada, cursive; Zilla Slab = Zilla Slab, serif; Zilla Slab Highlight = Zilla Slab Highlight, cursive'
  });
}
function TotalSoftPortfolio_Edit(Portfolio_ID) {
  jQuery('#Total_SoftPortfolio_Update').val(Portfolio_ID);
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolio_Edit', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_ID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      var data = response;
      jQuery('#TotalSoftPortfolio_Title').val(data[0]['TotalSoftPortfolio_Title']);
      jQuery('#TotalSoftPortfolio_Option').val(data[0]['TotalSoftPortfolio_Option']);
      jQuery('#TotalSoftPortfolio_AlbumCount').val(data[0]['TotalSoftPortfolio_AlbumCount']);
      jQuery('#TotalSoftPortfolio_AlbumCountHid').val(data[0]['TotalSoftPortfolio_AlbumCount']);
      for (var i = 2; i <= data[0]['TotalSoftPortfolio_AlbumCount']; i++) {
        jQuery('#TotalSoftHiddenRows_' + i).show();
        jQuery('#TotalSoftPortfolio_ImAlbum_' + i).show();
      }
    }
  });
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolio_Edit_Album', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_ID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
    },
    success: function (response) {
      var data = response;
      for (i = 0; i < data.length; i++) {
        jQuery('#TotalSoftPortfolio_ATitle' + parseInt(parseInt(i) + 1)).val(data[i]['TotalSoftPortfolio_ATitle']);
      }
    }
  });
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolio_Edit_Images', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_ID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
    },
    success: function (response) {
      var data = response;
      for (i = 0; i < data.length; i++) {
        var number = parseInt(i) + 1;
        if (i == 0) {
          jQuery('#TotalSoftPortfolioUl').html('<li id="TotalSoftPortfolioLi_' + number + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable2"><tr><td><input type="hidden" name="choose" value="' + number + '"><input type="checkbox" data-id="' + number + '" name="choose1" value="' + number + '">&nbsp 1</td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IT'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + number + '" name="TotalSoftPortfolio_IT_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + number + '" name="TotalSoftPortfolio_IDesc_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + number + '" name="TotalSoftPortfolio_ILink_' + number + '" value="' + data[i]['TotalSoftPortfolio_ILink'] + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + number + '" name="TotalSoftPortfolio_IONT_' + number + '" value="' + data[i]['TotalSoftPortfolio_IONT'] + '"></td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IA'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + number + '" name="TotalSoftPortfolio_IA_' + number + '"></td><td><img class="TotalSoftPortfolioImage" src="' + data[i]['TotalSoftPortfolio_IURL'] + '"><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IURL'] + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + number + '" name="TotalSoftPortfolio_IURL_' + number + '"></td><td onclick="TotalSoftImage_Copy(' + number + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + number + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="' + number + '" onclick="TotalSoftImage_Del(' + number + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + number + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + number + ')"></i></span></td></tr></table></li>');
        } else {
          if (i % 2 == 0) {
            jQuery('<li id="TotalSoftPortfolioLi_' + number + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable2"><tr><td><input type="hidden" name="choose" value="' + number + '"><input type="checkbox" name="choose1" data-id="' + number + '" value="' + number + '">&nbsp ' + number + '</td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IT'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + number + '" name="TotalSoftPortfolio_IT_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + number + '" name="TotalSoftPortfolio_IDesc_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + number + '" name="TotalSoftPortfolio_ILink_' + number + '" value="' + data[i]['TotalSoftPortfolio_ILink'] + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + number + '" name="TotalSoftPortfolio_IONT_' + number + '" value="' + data[i]['TotalSoftPortfolio_IONT'] + '"></td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IA'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + number + '" name="TotalSoftPortfolio_IA_' + number + '"></td><td><img class="TotalSoftPortfolioImage" src="' + data[i]['TotalSoftPortfolio_IURL'] + '"><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IURL'] + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + number + '" name="TotalSoftPortfolio_IURL_' + number + '"></td><td onclick="TotalSoftImage_Copy(' + number + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + number + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="' + number + '"onclick="TotalSoftImage_Del(' + number + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + number + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + number + ')"></i></span></td></tr></table></li>').insertAfter('#TotalSoftPortfolioUl li:nth-child(' + i + ')');
          } else {
            jQuery('<li id="TotalSoftPortfolioLi_' + number + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable3"><tr><td><input type="hidden" name="choose" value="' + number + '"><input type="checkbox" name="choose1" data-id="' + number + '" value="' + number + '">&nbsp ' + number + '</td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IT'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + number + '" name="TotalSoftPortfolio_IT_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + number + '" name="TotalSoftPortfolio_IDesc_' + number + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + number + '" name="TotalSoftPortfolio_ILink_' + number + '" value="' + data[i]['TotalSoftPortfolio_ILink'] + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + number + '" name="TotalSoftPortfolio_IONT_' + number + '" value="' + data[i]['TotalSoftPortfolio_IONT'] + '"></td><td><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IA'] + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + number + '" name="TotalSoftPortfolio_IA_' + number + '"></td><td><img class="TotalSoftPortfolioImage" src="' + data[i]['TotalSoftPortfolio_IURL'] + '"><input type="text" readonly value="' + data[i]['TotalSoftPortfolio_IURL'] + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + number + '" name="TotalSoftPortfolio_IURL_' + number + '"></td><td onclick="TotalSoftImage_Copy(' + number + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + number + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="' + number + '" onclick="TotalSoftImage_Del(' + number + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + number + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + number + ')"></i></span></td></tr></table></li>').insertAfter('#TotalSoftPortfolioUl li:nth-child(' + i + ')');
          }
        }
        jQuery('#TotalSoftPortfolio_IDesc_' + number).val(data[i]['TotalSoftPortfolio_IDesc']);
      }
      jQuery('#TotalSoftHidNum').val(data.length);
      Total_Soft_Portfolio_Editor();
      jQuery('.Total_Soft_Portfolio_AMD2').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_PortfolioAMMTable').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_PortfolioAMOTable').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_Portfolio_Save').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_Portfolio_Update').animate({ 'opacity': 1 }, 500);
      jQuery('#Total_Soft_Portfolio_ID').html('[Total_Soft_Portfolio id="' + Portfolio_ID + '"]');
      jQuery('#Total_Soft_Portfolio_TID').html('&lt;?php echo do_shortcode(&#039;[Total_Soft_Portfolio id="' + Portfolio_ID + '"]&#039;);?&gt');
      Total_Soft_Portfolio_Editor();
      setTimeout(function () {
        jQuery('.Total_Soft_Portfolio_AMD2').css('display', 'none');
        jQuery('.Total_Soft_PortfolioAMMTable').css('display', 'none');
        jQuery('.Total_Soft_PortfolioAMOTable').css('display', 'none');
        jQuery('.Total_Soft_Portfolio_Save').css('display', 'none');
        jQuery('.Total_Soft_Portfolio_Update').css('display', 'block');
        jQuery('.Total_Soft_Portfolio_AMD3').css('display', 'block');
        jQuery('.TS_Port_AM_Table_Div').css('display', 'block');
        jQuery('.Total_Soft_AMImageTable').css('display', 'table');
        jQuery('.Total_Soft_AMImageTable1').css('display', 'table');
        jQuery('.Total_Soft_AMShortTable').css('display', 'table');
      }, 500)
      setTimeout(function () {
        jQuery('.Total_Soft_Portfolio_AMD3').animate({ 'opacity': 1 }, 500);
        jQuery('.TS_Port_AM_Table_Div').animate({ 'opacity': 1 }, 500);
        jQuery('.Total_Soft_AMImageTable').animate({ 'opacity': 1 }, 500);
        jQuery('.Total_Soft_AMImageTable1').animate({ 'opacity': 1 }, 500);
        jQuery('.Total_Soft_AMShortTable').animate({ 'opacity': 1 }, 500);
        jQuery('.Total_Soft_Port_Loading').css('display', 'none');
      }, 600)
      jQuery('.totalsoft_delete_block').css('display', 'block');
      jQuery('.delete_td').css({ 'display': 'none' });
      jQuery('#totalsoft_delete_choose').click(function () {
        ifCheckedAll();
        jQuery('.Total_Soft_AMImageTable1').each(function (i, el) {
          jQuery(el).find('input[name=choose1]').each(function (ind, elem) {
            var check = jQuery(el).find(elem)[0];
            if (check.checked == true) {
              var getClickValue = jQuery(el).find('.Total_Soft_Portfolio_Del_Span_Yes').attr('onclick');
              var r1 = getClickValue.replace('Total_Soft_Portfolio_Del_Im_Yes(', '');
              var id = r1.replace(')', '');
              jQuery('#TotalSoftPortfolioLi_' + id).remove();
              jQuery('#TotalSoftHidNum').val(jQuery('#TotalSoftHidNum').val() - 1);
              jQuery("#TotalSoftPortfolioUl > li").each(function () {
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('id', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('name', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('id', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('name', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('id', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
                jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('name', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
                if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable2')) {
                  jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable2");
                } else if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable3')) {
                  jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable3");
                }
                if (jQuery(this).index() % 2 == 0) {
                  jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable2");
                } else {
                  jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable3");
                }
              });
            }
          });
        });
      });
      jQuery('#totalsoft_delete_all').click(function () {
        if (jQuery('input[name=totalsoft_delete_all]').is(":checked") == true) {
          jQuery('input[name=choose1]').prop('checked', true);
        }
        if (jQuery('input[name=totalsoft_delete_all]').is(":checked") == false) {
          jQuery('input[name=choose1]').prop('checked', false);
        }
      });
      function ifCheckedAll() {
        jQuery('#totalsoft_delete_all').click(function () {
          if (jQuery('input[name=totalsoft_delete_all]').is(":checked") == true) {
            jQuery('input[name=choose1]').prop('checked', true);
          }
          if (jQuery('input[name=totalsoft_delete_all]').is(":checked") == false) {
            jQuery('input[name=choose1]').prop('checked', false);
          }
        });
      }
    }
  });
}
function TotalSoftPortfolio_Del(Portfolio_ID) {
  jQuery('#Total_Soft_PortfolioAMOTable_tr_' + Portfolio_ID).find('.Total_Soft_Portfolio_Del_Span').addClass('Total_Soft_Portfolio_Del_Span1');
}
function TotalSoftPortfolio_Del_No(Portfolio_ID) {
  jQuery('#Total_Soft_PortfolioAMOTable_tr_' + Portfolio_ID).find('.Total_Soft_Portfolio_Del_Span').removeClass('Total_Soft_Portfolio_Del_Span1');
}
function TotalSoftPortfolio_Del_Yes(Portfolio_ID) {
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolio_Del', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_ID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      location.reload();
    }
  });
}
function TotalSoftPortfolio_Clone(Portfolio_ID) {
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolio_Clone', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_ID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      location.reload();
    }
  });
}
function TotalSoftPortfolio_ACount() {
  var TotalSoftPortfolio_AlbumCount = jQuery('#TotalSoftPortfolio_AlbumCount').val();
  var TotalSoftPortfolio_AlbumCountHid = parseInt(jQuery('#TotalSoftPortfolio_AlbumCountHid').val());
  if (TotalSoftPortfolio_AlbumCount > TotalSoftPortfolio_AlbumCountHid) {
    for (var i = TotalSoftPortfolio_AlbumCountHid + 1; i <= TotalSoftPortfolio_AlbumCount; i++) {
      jQuery('#TotalSoftHiddenRows_' + i).show(500);
      jQuery('#TotalSoftPortfolio_ImAlbum_' + i).show(500);
    }
  } else if (TotalSoftPortfolio_AlbumCount < TotalSoftPortfolio_AlbumCountHid) {
    for (var i = TotalSoftPortfolio_AlbumCountHid; i > TotalSoftPortfolio_AlbumCount; i--) {
      jQuery('#TotalSoftHiddenRows_' + i).hide(500);
      jQuery('#TotalSoftPortfolio_ImAlbum_' + i).hide(500);
    }
  }
  jQuery('#TotalSoftPortfolio_AlbumCountHid').val(TotalSoftPortfolio_AlbumCount);
}
function TotalSoftPortfolio_ImURL_Clicked() {
  var nIntervId = setInterval(function () {
    var code = jQuery('#TotalSoftPortfolio_ImURL_1').val();
    if (code.indexOf('img') > 0) {
      var s = code.split('src="');
      var src = s[1].split('"');
      jQuery('#TotalSoftPortfolio_ImURL_2').val(src[0]);
      if (jQuery('#TotalSoftPortfolio_ImURL_2').val().length > 0) {
        jQuery('#TotalSoftPortfolio_ImURL_1').val('');
        clearInterval(nIntervId);
      }
    }
  }, 100)
}
function Total_Soft_Portfolio_Img_Res() {
  jQuery('#TotalSoftPortfolio_ImTitle').val('');
  jQuery('#TotalSoftPortfolio_ImAlbum').val('1');
  jQuery('#TotalSoftPortfolio_ImURL_1').val('');
  jQuery('#TotalSoftPortfolio_ImURL_2').val('');
  tinyMCE.get('TotalSoftPortfolio_ImDesc').setContent('');
  jQuery('#TotalSoftPortfolio_ImLink').val('');
  jQuery('#TotalSoftPortfolio_ImONT').attr('checked', true);
  jQuery('#Total_Soft_Portfolio_UpdIm').animate({ 'opacity': 0 }, 500);
  setTimeout(function () {
    jQuery('#Total_Soft_Portfolio_UpdIm').css('display', 'none');
    jQuery('#Total_Soft_Portfolio_SavIm').css('display', 'inline');
  }, 300)
  setTimeout(function () {
    jQuery('#Total_Soft_Portfolio_SavIm').animate({ 'opacity': 1 }, 500);
  }, 500)
}
function Total_Soft_Portfolio_Img_Sav() {
  var TotalSoftHidNum = jQuery('#TotalSoftHidNum').val();
  var TotalSoftPortfolio_ImTitle = jQuery('#TotalSoftPortfolio_ImTitle').val();
  var TotalSoftPortfolio_ImAlbumNum = jQuery('#TotalSoftPortfolio_ImAlbum').val();
  var TotalSoftPortfolio_ImAlbum = jQuery('#TotalSoftPortfolio_ATitle' + TotalSoftPortfolio_ImAlbumNum).val();
  var TotalSoftPortfolio_ImURL_2 = jQuery('#TotalSoftPortfolio_ImURL_2').val();
  var TotalSoftPortfolio_ImDesc = tinyMCE.get('TotalSoftPortfolio_ImDesc').getContent();
  var TotalSoftPortfolio_ImLink = jQuery('#TotalSoftPortfolio_ImLink').val();
  var TotalSoftPortfolio_ImONT = jQuery('#TotalSoftPortfolio_ImONT').attr('checked');
  if (TotalSoftPortfolio_ImONT == 'checked') {
    TotalSoftPortfolio_ImONT = 'true';
  } else {
    TotalSoftPortfolio_ImONT = 'false';
  }
  if (TotalSoftPortfolio_ImURL_2 == '') {
    alert('You must uplaod an image then save the content.');
  } else {
    if (TotalSoftHidNum == '0') {
      jQuery('#TotalSoftPortfolioUl').addClass('Total_Soft_AMImageTableCH');
      jQuery('#TotalSoftPortfolioUl').html('<li id="TotalSoftPortfolioLi_1"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable2 Total_Soft_AMImageTableCH"><tr><td><input type="hidden" name="choose" value="1"><input type="checkbox" data-id="1" name="choose1" value="1">&nbsp 1</td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImTitle + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_1" name="TotalSoftPortfolio_IT_1"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_1" name="TotalSoftPortfolio_IDesc_1" value=""><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_1" name="TotalSoftPortfolio_ILink_1" value="' + TotalSoftPortfolio_ImLink + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_1" name="TotalSoftPortfolio_IONT_1" value="' + TotalSoftPortfolio_ImONT + '"></td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImAlbum + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_1" name="TotalSoftPortfolio_IA_1"></td><td><img class="TotalSoftPortfolioImage" src="' + TotalSoftPortfolio_ImURL_2 + '"><input type="text" readonly value="' + TotalSoftPortfolio_ImURL_2 + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_1" name="TotalSoftPortfolio_IURL_1"></td><td onclick="TotalSoftImage_Copy(1)"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(1)"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="1" onclick="TotalSoftImage_Del(1)"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(1)"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(1)"></i></span></td></tr></table></li>');
    } else {
      if (TotalSoftHidNum % 2 == 1) {
        jQuery('<li id="TotalSoftPortfolioLi_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable3 "><tr><td><input type="hidden" name="choose" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="checkbox" name="choose1" data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '">&nbsp ' + parseInt(parseInt(TotalSoftHidNum) + 1) + '</td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImTitle + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value=""><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_ImLink + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_ImONT + '"></td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImAlbum + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td><img class="TotalSoftPortfolioImage" src="' + TotalSoftPortfolio_ImURL_2 + '"><input type="text" readonly value="' + TotalSoftPortfolio_ImURL_2 + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td onclick="TotalSoftImage_Copy(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"" class="totalsoft totalsoft-trash" onclick="TotalSoftImage_Del(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i></span></td></tr></table></li>').insertAfter('#TotalSoftPortfolioUl li:nth-child(' + TotalSoftHidNum + ')');
      } else {
        jQuery('<li id="TotalSoftPortfolioLi_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable2 "><tr><td><input type="hidden" name="choose" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="checkbox" name="choose1" data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '">&nbsp ' + parseInt(parseInt(TotalSoftHidNum) + 1) + '</td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImTitle + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value=""><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_ImLink + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_ImONT + '"></td><td><input type="text" readonly value="' + TotalSoftPortfolio_ImAlbum + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td><img class="TotalSoftPortfolioImage" src="' + TotalSoftPortfolio_ImURL_2 + '"><input type="text" readonly value="' + TotalSoftPortfolio_ImURL_2 + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td onclick="TotalSoftImage_Copy(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"  onclick="TotalSoftImage_Del(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i></span></td></tr></table></li>').insertAfter('#TotalSoftPortfolioUl li:nth-child(' + TotalSoftHidNum + ')');
      }
    }
    jQuery('#TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1)).val(TotalSoftPortfolio_ImDesc);
    jQuery('#TotalSoftHidNum').val(parseInt(parseInt(TotalSoftHidNum) + 1));
    Total_Soft_Portfolio_Img_Res();
    TS_Port_Add_Image_Button_Close();
  }
}
function TotalSoftImage_Copy(TotalSoftImage_ID) {
  var TotalSoftHidNum = jQuery('#TotalSoftHidNum').val();
  var TotalSoftPortfolio_IT = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IA = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IURL = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IDesc = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').val();
  var TotalSoftPortfolio_ILink = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').val();
  var TotalSoftPortfolio_IONT = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').val();
  jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).after('<li id="TotalSoftPortfolioLi_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><table class="Total_Soft_AMImageTable1 Total_Soft_AMImageTable2"><tr><td><input type="hidden" name="choose" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="checkbox" data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="choose1" value="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '">&nbsp ' + parseInt(parseInt(TotalSoftHidNum) + 1) + '</td><td><input type="text" readonly value="' + TotalSoftPortfolio_IT + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_ILink_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_ILink + '"><input type="text" style="display:none;" class="TotalSoftPortfolio_ImONT" id="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IONT_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" value="' + TotalSoftPortfolio_IONT + '"></td><td><input type="text" readonly value="' + TotalSoftPortfolio_IA + '" class="Total_Soft_Select Total_Soft_Select1" id="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IA_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td><img class="TotalSoftPortfolioImage" src="' + TotalSoftPortfolio_IURL + '"><input type="text" readonly value="' + TotalSoftPortfolio_IURL + '" class="Total_Soft_Select Total_Soft_Select1" style="display:none;" id="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '" name="TotalSoftPortfolio_IURL_' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"></td><td onclick="TotalSoftImage_Copy(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-file-text"></i></td><td onclick="TotalSoftImage_Edit(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"><i class="totalsoft totalsoft-pencil"></i></td><td style="display:none;" class="totalsoft-trash1"><i class="totalsoft totalsoft-trash" data-id="' + parseInt(parseInt(TotalSoftHidNum) + 1) + '"  onclick="TotalSoftImage_Del(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><span class="Total_Soft_Portfolio_Del_Span"><i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="Total_Soft_Portfolio_Del_Im_Yes(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i><i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="Total_Soft_Portfolio_Del_Im_No(' + parseInt(parseInt(TotalSoftHidNum) + 1) + ')"></i></span></td></tr></table></li>').insertAfter('#TotalSoftPortfolioUl li:nth-child(' + TotalSoftImage_ID + ')');
  jQuery('#TotalSoftPortfolio_IDesc_' + parseInt(parseInt(TotalSoftHidNum) + 1)).val(TotalSoftPortfolio_IDesc);
  jQuery('#TotalSoftHidNum').val(parseInt(parseInt(TotalSoftHidNum) + 1));
  jQuery("#TotalSoftPortfolioUl > li").each(function () {
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(1)').html('<input type="checkbox" name="choose1" value="' + parseInt(parseInt(jQuery(this).index()) + 1) + '">&nbsp' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('id', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('name', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('id', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('name', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('id', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('name', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable2')) {
      jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable2");
    } else if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable3')) {
      jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable3");
    }
    if (jQuery(this).index() % 2 == 0) {
      jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable2");
    } else {
      jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable3");
    }
  });
}
function TotalSoftImage_Edit(TotalSoftImage_ID) {
  TS_Port_Add_Image_Button();
  var TotalSoftPortfolio_IT = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IA = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IURL = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').val();
  var TotalSoftPortfolio_IDesc = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').val();
  var TotalSoftPortfolio_ILink = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').val();
  var TotalSoftPortfolio_IONT = jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').val();
  jQuery('#TotalSoftHidUpdate').val(TotalSoftImage_ID);
  jQuery('#Total_Soft_Portfolio_SavIm').animate({ 'opacity': 0 }, 500);
  setTimeout(function () {
    jQuery('#Total_Soft_Portfolio_SavIm').css('display', 'none');
    jQuery('#Total_Soft_Portfolio_UpdIm').css('display', 'inline');
  }, 300)
  setTimeout(function () {
    jQuery('#Total_Soft_Portfolio_UpdIm').animate({ 'opacity': 1 }, 500);
  }, 500)
  jQuery('#TotalSoftPortfolio_ImTitle').val(TotalSoftPortfolio_IT);
  var albumsCount = document.querySelector("#TotalSoftPortfolio_AlbumCount").value;
  for (var i = 1; i <= albumsCount; i++) {
    if (jQuery('#TotalSoftPortfolio_ATitle' + i).val() == TotalSoftPortfolio_IA) {
      jQuery('#TotalSoftPortfolio_ImAlbum').val(i);
    }
  }
  jQuery('#TotalSoftPortfolio_ImURL_2').val(TotalSoftPortfolio_IURL);
  tinyMCE.get('TotalSoftPortfolio_ImDesc').setContent(TotalSoftPortfolio_IDesc);
  jQuery('#TotalSoftPortfolio_ImLink').val(TotalSoftPortfolio_ILink);
  var TotalSoftPortfolio_ImONT = jQuery('#TotalSoftPortfolio_ImONT').attr('checked');
  if (TotalSoftPortfolio_IONT == 'true') {
    jQuery('#TotalSoftPortfolio_ImONT').attr('checked', true);
  } else {
    jQuery('#TotalSoftPortfolio_ImONT').attr('checked', false);
  }
}
function TotalSoftImage_Del(TotalSoftImage_ID) {
  jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_Portfolio_Del_Span').addClass('Total_Soft_Portfolio_Del_Span1');
}
function Total_Soft_Portfolio_Del_Im_No(TotalSoftImage_ID) {
  jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_Portfolio_Del_Span').removeClass('Total_Soft_Portfolio_Del_Span1');
}
function Total_Soft_Portfolio_Del_Im_Yes(TotalSoftImage_ID) {
  jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).remove();
  jQuery('#TotalSoftHidNum').val(jQuery('#TotalSoftHidNum').val() - 1);
  jQuery("#TotalSoftPortfolioUl > li").each(function () {
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(1)').html('<input type="checkbox" name="choose1" value="' + parseInt(parseInt(jQuery(this).index()) + 1) + '">&nbsp' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('id', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('name', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('id', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('name', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('id', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('name', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
    if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable2')) {
      jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable2");
    } else if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable3')) {
      jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable3");
    }
    if (jQuery(this).index() % 2 == 0) {
      jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable2");
    } else {
      jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable3");
    }
  });
}
function TotalSoftPortfolioUlSort() {
  jQuery('#TotalSoftPortfolioUl').sortable({
    update: function () {
      jQuery("#TotalSoftPortfolioUl > li").each(function () {
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(1)').html('<input type="checkbox" name="choose1" value="' + parseInt(parseInt(jQuery(this).index()) + 1) + '">&nbsp' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IT_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IA_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('id', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').attr('name', 'TotalSoftPortfolio_IURL_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('id', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').attr('name', 'TotalSoftPortfolio_IDesc_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('id', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').attr('name', 'TotalSoftPortfolio_ILink_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('id', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
        jQuery(this).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').attr('name', 'TotalSoftPortfolio_IONT_' + parseInt(parseInt(jQuery(this).index()) + 1));
        if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable2')) {
          jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable2");
        } else if (jQuery(this).find('.Total_Soft_AMImageTable1').hasClass('Total_Soft_AMImageTable3')) {
          jQuery(this).find('.Total_Soft_AMImageTable1').removeClass("Total_Soft_AMImageTable3");
        }
        if (jQuery(this).index() % 2 == 0) {
          jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable2");
        } else {
          jQuery(this).find('.Total_Soft_AMImageTable1').addClass("Total_Soft_AMImageTable3");
        }
      });
    }
  });
}
function Total_Soft_Portfolio_Img_Update() {
  var TotalSoftImage_ID = jQuery('#TotalSoftHidUpdate').val();
  var TotalSoftPortfolio_IT = jQuery('#TotalSoftPortfolio_ImTitle').val();
  var TotalSoftPortfolio_ImAlbumNum = jQuery('#TotalSoftPortfolio_ImAlbum').val();
  var TotalSoftPortfolio_IA = jQuery('#TotalSoftPortfolio_ATitle' + TotalSoftPortfolio_ImAlbumNum).val();
  var TotalSoftPortfolio_IURL = jQuery('#TotalSoftPortfolio_ImURL_2').val();
  var TotalSoftPortfolio_ImDesc = tinyMCE.get('TotalSoftPortfolio_ImDesc').getContent();
  var TotalSoftPortfolio_ImLink = jQuery('#TotalSoftPortfolio_ImLink').val();
  var TotalSoftPortfolio_ImONT = jQuery('#TotalSoftPortfolio_ImONT').attr('checked');
  if (TotalSoftPortfolio_ImONT == 'checked') {
    TotalSoftPortfolio_ImONT = 'true';
  } else {
    TotalSoftPortfolio_ImONT = 'false';
  }
  if (TotalSoftPortfolio_IURL == '') {
    alert('You must upload an image then click to update the content.');
  } else {
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.Total_Soft_Select').val(TotalSoftPortfolio_IT);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(3)').find('.Total_Soft_Select').val(TotalSoftPortfolio_IA);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.Total_Soft_Select').val(TotalSoftPortfolio_IURL);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(4)').find('.TotalSoftPortfolioImage').attr('src', TotalSoftPortfolio_IURL);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImDesc').val(TotalSoftPortfolio_ImDesc);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImLink').val(TotalSoftPortfolio_ImLink);
    jQuery('#TotalSoftPortfolioLi_' + TotalSoftImage_ID).find('.Total_Soft_AMImageTable1 td:nth-child(2)').find('.TotalSoftPortfolio_ImONT').val(TotalSoftPortfolio_ImONT);
    Total_Soft_Portfolio_Img_Res();
    TS_Port_Add_Image_Button_Close()
  }
}
function TS_Port_IT_FD_Clicked(type) {
  if (type == '1') {
    jQuery('#TotalSoftPortfolio_ImURL_1').val('');
    jQuery('#TotalSoftPortfolio_ImURL_2').val('');
  }
}
// General Options
function Total_Soft_Portfolio_Opt_AMD2_But1() {
  jQuery('.Total_Soft_Portfolio_AMD2').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_PortfolioTMMTable').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_PortfolioTMOTable').animate({ 'opacity': 0 }, 500);
  jQuery('.Total_Soft_Portfolio_Save_Option').animate({ 'opacity': 1 }, 500);
  jQuery('.Total_Soft_Portfolio_Update_Option').animate({ 'opacity': 0 }, 500);
  setTimeout(function () {
    jQuery('.Total_Soft_Port_Color').alphaColorPicker();
    jQuery('.Total_Soft_Port_Color1').alphaColorPicker();
    jQuery('.wp-picker-holder').addClass('alpha-picker-holder');
    jQuery('.Total_Soft_Portfolio_AMD2').css('display', 'none');
    jQuery('.Total_Soft_PortfolioTMMTable').css('display', 'none');
    jQuery('.Total_Soft_PortfolioTMOTable').css('display', 'none');
    jQuery('.Total_Soft_Portfolio_Save_Option').css('display', 'block');
    jQuery('.Total_Soft_Portfolio_Update_Option').css('display', 'none');
    jQuery('.Total_Soft_Portfolio_AMD3').css('display', 'block');
    jQuery('#Total_Soft_Port_AMSetDiv_1').css('display', 'block');
    jQuery('#Total_Soft_Port_AMSet_Table').css('display', 'block');
  }, 300)
  setTimeout(function () {
    jQuery('.Total_Soft_Portfolio_AMD3').animate({ 'opacity': 1 }, 500);
    jQuery('#Total_Soft_Port_AMSetDiv_1').animate({ 'opacity': 1 }, 500);
    jQuery('#Total_Soft_Port_AMSet_Table').animate({ 'opacity': 1 }, 500);
  }, 400)
  TotalSoft_Portfolio_Out();
  TS_Port_TM_But('1', 'GO');
}
function TS_Port_TM_But(type, col_id) {
  jQuery('.TS_Port_Option_Div').css('display', 'none');
  jQuery('.Total_Soft_Port_AMSetDiv_Button').removeClass('Total_Soft_Port_AMSetDiv_Button_C');
  jQuery('#TS_Port_TM_TBut_' + type + '_' + col_id).addClass('Total_Soft_Port_AMSetDiv_Button_C');
  jQuery('#Total_Soft_Port_AMSetTable_' + type + '_' + col_id).css('display', 'block');
}
function TotalSoftPortfolio_Edit_Option(Portfolio_OptID) {
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolioOpt_Edit', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_OptID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      var data = response;
      jQuery('#Total_SoftPortfolio_Update').val(Portfolio_OptID);
      jQuery('#TotalSoftPortfolio_SetName').val(data[0]['TotalSoftPortfolio_SetName']);
      jQuery('#TotalSoftPortfolio_SetType').val(data[0]['TotalSoftPortfolio_SetType']);
      if (data[0]['TotalSoftPortfolio_SetType'] == 'Total Soft Portfolio') {
        jQuery('#TotalSoft_PG_TG_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_TG_02').val(data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_TG_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_TG_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_TG_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_TG_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_TG_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_TG_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_TG_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_TG_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_TG_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_TG_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_TG_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_TG_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_TG_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_TG_16').val(data[0]['TotalSoft_PG_1_16']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Elastic Grid') {
        if (data[0]['TotalSoft_PG_1_02'] == 'true') {
          data[0]['TotalSoft_PG_1_02'] = true;
        } else {
          data[0]['TotalSoft_PG_1_02'] = false;
        }
        if (data[0]['TotalSoft_PG_1_04'] == 'true') {
          data[0]['TotalSoft_PG_1_04'] = true;
        } else {
          data[0]['TotalSoft_PG_1_04'] = false;
        }
        if (data[0]['TotalSoft_PG_1_06'] == 'true') {
          data[0]['TotalSoft_PG_1_06'] = true;
        } else {
          data[0]['TotalSoft_PG_1_06'] = false;
        }
        jQuery('#TotalSoft_PG_EG_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_EG_02').attr('checked', data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_EG_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_EG_04').attr('checked', data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_EG_05').val(data[0]['TotalSoft_PG_1_05'] / 500);
        jQuery('#TotalSoft_PG_EG_06').attr('checked', data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_EG_07').val(data[0]['TotalSoft_PG_1_07'] / 500);
        jQuery('#TotalSoft_PG_EG_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_EG_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_EG_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_EG_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_EG_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_EG_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_EG_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_EG_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_EG_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_EG_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_EG_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_EG_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_EG_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_EG_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_EG_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_EG_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_EG_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_EG_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_EG_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_EG_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_EG_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_EG_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_EG_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_EG_31').val(data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_EG_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_EG_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_EG_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_EG_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_EG_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_EG_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_EG_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_EG_39').val(data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Filterable Grid') {
        if (data[0]['TotalSoft_PG_1_05'] == 'true') {
          data[0]['TotalSoft_PG_1_05'] = true;
        } else {
          data[0]['TotalSoft_PG_1_05'] = false;
        }
        if (data[0]['TotalSoft_PG_1_18'] == 'true') {
          data[0]['TotalSoft_PG_1_18'] = true;
        } else {
          data[0]['TotalSoft_PG_1_18'] = false;
        }
        if (data[0]['TotalSoft_PG_1_19'] == '#ffffff') {
          data[0]['TotalSoft_PG_1_19'] = 'Effect 1';
        }
        jQuery('#TotalSoft_PG_FG_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_FG_02').val(data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_FG_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_FG_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_FG_05').attr('checked', data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_FG_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_FG_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_FG_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_FG_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_FG_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_FG_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_FG_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_FG_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_FG_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_FG_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_FG_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_FG_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_FG_18').attr('checked', data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_FG_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_FG_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_FG_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_FG_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_FG_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_FG_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_FG_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_FG_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_FG_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_FG_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_FG_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_FG_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_FG_31').val(data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_FG_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_FG_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_FG_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_FG_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_FG_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_FG_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_FG_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_FG_39').val(data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Gallery Portfolio/Content Popup') {
        if (data[0]['TotalSoft_PG_1_39'] == 'true') {
          data[0]['TotalSoft_PG_1_39'] = true;
        } else {
          data[0]['TotalSoft_PG_1_39'] = false;
        }
        jQuery('#TotalSoft_PG_CP_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_CP_02').val(data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_CP_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_CP_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_CP_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_CP_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_CP_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_CP_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_CP_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_CP_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_CP_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_CP_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_CP_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_CP_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_CP_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_CP_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_CP_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_CP_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_CP_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_CP_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_CP_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_CP_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_CP_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_CP_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_CP_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_CP_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_CP_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_CP_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_CP_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_CP_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_CP_31').val(data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_CP_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_CP_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_CP_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_CP_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_CP_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_CP_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_CP_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_CP_39').attr('checked', data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Slider Portfolio') {
        if (data[0]['TotalSoft_PG_1_01'] == 'true') {
          data[0]['TotalSoft_PG_1_01'] = true;
        } else {
          data[0]['TotalSoft_PG_1_01'] = false;
        }
        if (data[0]['TotalSoft_PG_1_09'] == 'true') {
          data[0]['TotalSoft_PG_1_09'] = true;
        } else {
          data[0]['TotalSoft_PG_1_09'] = false;
        }
        if (data[0]['TotalSoft_PG_1_15'] == 'true') {
          data[0]['TotalSoft_PG_1_15'] = true;
        } else {
          data[0]['TotalSoft_PG_1_15'] = false;
        }
        if (data[0]['TotalSoft_PG_1_24'] == 'true') {
          data[0]['TotalSoft_PG_1_24'] = true;
        } else {
          data[0]['TotalSoft_PG_1_24'] = false;
        }
        if (data[0]['TotalSoft_PG_1_39'] == 'true') {
          data[0]['TotalSoft_PG_1_39'] = true;
        } else {
          data[0]['TotalSoft_PG_1_39'] = false;
        }
        jQuery('#TotalSoft_PG_SP_01').attr('checked', data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_SP_02').val(data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_SP_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_SP_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_SP_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_SP_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_SP_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_SP_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_SP_09').attr('checked', data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_SP_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_SP_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_SP_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_SP_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_SP_14').val(data[0]['TotalSoft_PG_1_14'] * 10);
        jQuery('#TotalSoft_PG_SP_15').attr('checked', data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_SP_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_SP_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_SP_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_SP_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_SP_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_SP_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_SP_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_SP_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_SP_24').attr('checked', data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_SP_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_SP_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_SP_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_SP_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_SP_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_SP_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_SP_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_SP_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_SP_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_SP_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_SP_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_SP_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_SP_39').attr('checked', data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Gallery Album Animation') {
        if (data[0]['TotalSoft_PG_1_03'] == 'true') {
          data[0]['TotalSoft_PG_1_03'] = true;
        } else {
          data[0]['TotalSoft_PG_1_03'] = false;
        }
        if (data[0]['TotalSoft_PG_1_08'] == 'true') {
          data[0]['TotalSoft_PG_1_08'] = true;
        } else {
          data[0]['TotalSoft_PG_1_08'] = false;
        }
        if (data[0]['TotalSoft_PG_1_13'] == 'true') {
          data[0]['TotalSoft_PG_1_13'] = true;
        } else {
          data[0]['TotalSoft_PG_1_13'] = false;
        }
        if (data[0]['TotalSoft_PG_1_14'] == 'true') {
          data[0]['TotalSoft_PG_1_14'] = true;
        } else {
          data[0]['TotalSoft_PG_1_14'] = false;
        }
        if (data[0]['TotalSoft_PG_1_23'] == 'true') {
          data[0]['TotalSoft_PG_1_23'] = true;
        } else {
          data[0]['TotalSoft_PG_1_23'] = false;
        }
        if (data[0]['TotalSoft_PG_1_31'] == 'true') {
          data[0]['TotalSoft_PG_1_31'] = true;
        } else {
          data[0]['TotalSoft_PG_1_31'] = false;
        }
        if (data[0]['TotalSoft_PG_1_38'] == 'true') {
          data[0]['TotalSoft_PG_1_38'] = true;
        } else {
          data[0]['TotalSoft_PG_1_38'] = false;
        }
        jQuery('#TotalSoft_PG_GA_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_GA_02').val(data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_GA_03').attr('checked', data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_GA_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_GA_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_GA_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_GA_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_GA_08').attr('checked', data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_GA_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_GA_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_GA_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_GA_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_GA_13').attr('checked', data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_GA_14').attr('checked', data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_GA_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_GA_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_GA_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_GA_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_GA_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_GA_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_GA_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_GA_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_GA_23').attr('checked', data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_GA_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_GA_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_GA_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_GA_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_GA_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_GA_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_GA_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_GA_31').attr('checked', data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_GA_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_GA_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_GA_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_GA_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_GA_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_GA_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_GA_38').attr('checked', data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_GA_39').val(data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Portfolio / Hover Effects') {
        if (data[0]['TotalSoft_PG_1_02'] == 'true') {
          data[0]['TotalSoft_PG_1_02'] = true;
        } else {
          data[0]['TotalSoft_PG_1_02'] = false;
        }
        if (data[0]['TotalSoft_PG_1_15'] == 'true') {
          data[0]['TotalSoft_PG_1_15'] = true;
        } else {
          data[0]['TotalSoft_PG_1_15'] = false;
        }
        if (data[0]['TotalSoft_PG_1_33'] == 'true') {
          data[0]['TotalSoft_PG_1_33'] = true;
        } else {
          data[0]['TotalSoft_PG_1_33'] = false;
        }
        jQuery('#TotalSoft_PG_PH_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_PH_02').attr('checked', data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_PH_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_PH_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_PH_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_PH_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_PH_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_PH_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_PH_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_PH_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_PH_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_PH_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_PH_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_PH_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_PH_15').attr('checked', data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_PH_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_PH_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_PH_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_PH_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_PH_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_PH_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_PH_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_PH_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_PH_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_PH_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_PH_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_PH_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_PH_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_PH_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_PH_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_PH_31').val(data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_PH_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_PH_33').attr('checked', data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_PH_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_PH_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_PH_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_PH_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_PH_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_PH_39').val(data[0]['TotalSoft_PG_1_39']);
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Lightbox Gallery') {
        if (data[0]['TotalSoft_PG_1_02'] == 'true') {
          data[0]['TotalSoft_PG_1_02'] = true;
        } else {
          data[0]['TotalSoft_PG_1_02'] = false;
        }
        jQuery('#TotalSoft_PG_LG_01').val(data[0]['TotalSoft_PG_1_01']);
        jQuery('#TotalSoft_PG_LG_02').attr('checked', data[0]['TotalSoft_PG_1_02']);
        jQuery('#TotalSoft_PG_LG_03').val(data[0]['TotalSoft_PG_1_03']);
        jQuery('#TotalSoft_PG_LG_04').val(data[0]['TotalSoft_PG_1_04']);
        jQuery('#TotalSoft_PG_LG_05').val(data[0]['TotalSoft_PG_1_05']);
        jQuery('#TotalSoft_PG_LG_06').val(data[0]['TotalSoft_PG_1_06']);
        jQuery('#TotalSoft_PG_LG_07').val(data[0]['TotalSoft_PG_1_07']);
        jQuery('#TotalSoft_PG_LG_08').val(data[0]['TotalSoft_PG_1_08']);
        jQuery('#TotalSoft_PG_LG_09').val(data[0]['TotalSoft_PG_1_09']);
        jQuery('#TotalSoft_PG_LG_10').val(data[0]['TotalSoft_PG_1_10']);
        jQuery('#TotalSoft_PG_LG_11').val(data[0]['TotalSoft_PG_1_11']);
        jQuery('#TotalSoft_PG_LG_12').val(data[0]['TotalSoft_PG_1_12']);
        jQuery('#TotalSoft_PG_LG_13').val(data[0]['TotalSoft_PG_1_13']);
        jQuery('#TotalSoft_PG_LG_14').val(data[0]['TotalSoft_PG_1_14']);
        jQuery('#TotalSoft_PG_LG_15').val(data[0]['TotalSoft_PG_1_15']);
        jQuery('#TotalSoft_PG_LG_16').val(data[0]['TotalSoft_PG_1_16']);
        jQuery('#TotalSoft_PG_LG_17').val(data[0]['TotalSoft_PG_1_17']);
        jQuery('#TotalSoft_PG_LG_18').val(data[0]['TotalSoft_PG_1_18']);
        jQuery('#TotalSoft_PG_LG_19').val(data[0]['TotalSoft_PG_1_19']);
        jQuery('#TotalSoft_PG_LG_20').val(data[0]['TotalSoft_PG_1_20']);
        jQuery('#TotalSoft_PG_LG_21').val(data[0]['TotalSoft_PG_1_21']);
        jQuery('#TotalSoft_PG_LG_22').val(data[0]['TotalSoft_PG_1_22']);
        jQuery('#TotalSoft_PG_LG_23').val(data[0]['TotalSoft_PG_1_23']);
        jQuery('#TotalSoft_PG_LG_24').val(data[0]['TotalSoft_PG_1_24']);
        jQuery('#TotalSoft_PG_LG_25').val(data[0]['TotalSoft_PG_1_25']);
        jQuery('#TotalSoft_PG_LG_26').val(data[0]['TotalSoft_PG_1_26']);
        jQuery('#TotalSoft_PG_LG_27').val(data[0]['TotalSoft_PG_1_27']);
        jQuery('#TotalSoft_PG_LG_28').val(data[0]['TotalSoft_PG_1_28']);
        jQuery('#TotalSoft_PG_LG_29').val(data[0]['TotalSoft_PG_1_29']);
        jQuery('#TotalSoft_PG_LG_30').val(data[0]['TotalSoft_PG_1_30']);
        jQuery('#TotalSoft_PG_LG_31').val(data[0]['TotalSoft_PG_1_31']);
        jQuery('#TotalSoft_PG_LG_32').val(data[0]['TotalSoft_PG_1_32']);
        jQuery('#TotalSoft_PG_LG_33').val(data[0]['TotalSoft_PG_1_33']);
        jQuery('#TotalSoft_PG_LG_34').val(data[0]['TotalSoft_PG_1_34']);
        jQuery('#TotalSoft_PG_LG_35').val(data[0]['TotalSoft_PG_1_35']);
        jQuery('#TotalSoft_PG_LG_36').val(data[0]['TotalSoft_PG_1_36']);
        jQuery('#TotalSoft_PG_LG_37').val(data[0]['TotalSoft_PG_1_37']);
        jQuery('#TotalSoft_PG_LG_38').val(data[0]['TotalSoft_PG_1_38']);
        jQuery('#TotalSoft_PG_LG_39').val(data[0]['TotalSoft_PG_1_39']);
      }
      setTimeout(function () {
        jQuery('.Total_Soft_Port_Color').alphaColorPicker();
        jQuery('.wp-picker-holder').addClass('alpha-picker-holder');
        TotalSoft_Portfolio_Out();
      }, 500)
    }
  });
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolioOpt_Edit1', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_OptID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
    },
    success: function (response) {
      var data = response;
      jQuery('#TotalSoftPortfolio_SetType').hide(500);
      if (data[0]['TotalSoftPortfolio_SetType'] == 'Total Soft Portfolio') {
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_1').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_1').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('1', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Elastic Grid') {
        jQuery('#TotalSoft_PG_EG_40').val(data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_EG_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_EG_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_EG_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_EG_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_EG_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_EG_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_EG_47').val(data[0]['TotalSoft_PG_2_08']);
        jQuery('#TotalSoft_PG_EG_48').val(data[0]['TotalSoft_PG_2_09']);
        jQuery('#TotalSoft_PG_EG_49').val(data[0]['TotalSoft_PG_2_10']);
        jQuery('#TotalSoft_PG_EG_50').val(data[0]['TotalSoft_PG_2_11']);
        jQuery('#TotalSoft_PG_EG_51').val(data[0]['TotalSoft_PG_2_12']);
        jQuery('#TotalSoft_PG_EG_52').val(data[0]['TotalSoft_PG_2_13']);
        jQuery('#TotalSoft_PG_EG_53').val(data[0]['TotalSoft_PG_2_14']);
        jQuery('#TotalSoft_PG_EG_54').val(data[0]['TotalSoft_PG_2_15']);
        jQuery('#TotalSoft_PG_EG_55').val(data[0]['TotalSoft_PG_2_16']);
        jQuery('#TotalSoft_PG_EG_56').val(data[0]['TotalSoft_PG_2_17']);
        jQuery('#TotalSoft_PG_EG_57').val(data[0]['TotalSoft_PG_2_18']);
        jQuery('#TotalSoft_PG_EG_60').val(data[0]['TotalSoft_PG_2_21']);
        jQuery('#TotalSoft_PG_EG_61').val(data[0]['TotalSoft_PG_2_22']);
        jQuery('#TotalSoft_PG_EG_62').val(data[0]['TotalSoft_PG_2_23']);
        jQuery('#TotalSoft_PG_EG_64').val(data[0]['TotalSoft_PG_2_25']);
        jQuery('#TotalSoft_PG_EG_65').val(data[0]['TotalSoft_PG_2_26']);
        jQuery('#TotalSoft_PG_EG_66').val(data[0]['TotalSoft_PG_2_27']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_2').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_2').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('2', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Filterable Grid') {
        jQuery('#TotalSoft_PG_FG_40').val(data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_FG_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_FG_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_FG_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_FG_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_FG_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_FG_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_FG_47').val(data[0]['TotalSoft_PG_2_08']);
        jQuery('#TotalSoft_PG_FG_48').val(data[0]['TotalSoft_PG_2_09']);
        jQuery('#TotalSoft_PG_FG_49').val(data[0]['TotalSoft_PG_2_10']);
        jQuery('#TotalSoft_PG_FG_50').val(data[0]['TotalSoft_PG_2_11']);
        jQuery('#TotalSoft_PG_FG_51').val(data[0]['TotalSoft_PG_2_12']);
        jQuery('#TotalSoft_PG_FG_52').val(data[0]['TotalSoft_PG_2_13']);
        jQuery('#TotalSoft_PG_FG_53').val(data[0]['TotalSoft_PG_2_14']);
        jQuery('#TotalSoft_PG_FG_54').val(data[0]['TotalSoft_PG_2_15']);
        jQuery('#TotalSoft_PG_FG_55').val(data[0]['TotalSoft_PG_2_16']);
        jQuery('#TotalSoft_PG_FG_56').val(data[0]['TotalSoft_PG_2_17']);
        jQuery('#TotalSoft_PG_FG_57').val(data[0]['TotalSoft_PG_2_18']);
        jQuery('#TotalSoft_PG_FG_58').val(data[0]['TotalSoft_PG_2_19']);
        jQuery('#TotalSoft_PG_FG_59').val(data[0]['TotalSoft_PG_2_20']);
        jQuery('#TotalSoft_PG_FG_60').val(data[0]['TotalSoft_PG_2_21']);
        jQuery('#TotalSoft_PG_FG_61').val(data[0]['TotalSoft_PG_2_22']);
        jQuery('#TotalSoft_PG_FG_62').val(data[0]['TotalSoft_PG_2_23']);
        jQuery('#TotalSoft_PG_FG_63').val(data[0]['TotalSoft_PG_2_24']);
        jQuery('#TotalSoft_PG_FG_64').val(data[0]['TotalSoft_PG_2_25']);
        jQuery('#TotalSoft_PG_FG_65').val(data[0]['TotalSoft_PG_2_26']);
        jQuery('#TotalSoft_PG_FG_66').val(data[0]['TotalSoft_PG_2_27']);
        jQuery('#TotalSoft_PG_FG_67').val(data[0]['TotalSoft_PG_2_28']);
        jQuery('#TotalSoft_PG_FG_68').val(data[0]['TotalSoft_PG_2_29']);
        jQuery('#TotalSoft_PG_FG_69').val(data[0]['TotalSoft_PG_2_30']);
        jQuery('#TotalSoft_PG_FG_70').val(data[0]['TotalSoft_PG_2_31']);
        jQuery('#TotalSoft_PG_FG_71').val(data[0]['TotalSoft_PG_2_32']);
        jQuery('#TotalSoft_PG_FG_72').val(data[0]['TotalSoft_PG_2_33']);
        jQuery('#TotalSoft_PG_FG_73').val(data[0]['TotalSoft_PG_2_34']);
        jQuery('#TotalSoft_PG_FG_74').val(data[0]['TotalSoft_PG_2_35']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_3').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_3').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('3', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Gallery Portfolio/Content Popup') {
        if (data[0]['TotalSoft_PG_2_18'] == 'true') {
          data[0]['TotalSoft_PG_2_18'] = true;
        } else {
          data[0]['TotalSoft_PG_2_18'] = false;
        }
        jQuery('#TotalSoft_PG_CP_40').val(data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_CP_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_CP_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_CP_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_CP_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_CP_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_CP_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_CP_47').val(data[0]['TotalSoft_PG_2_08']);
        jQuery('#TotalSoft_PG_CP_48').val(data[0]['TotalSoft_PG_2_09']);
        jQuery('#TotalSoft_PG_CP_49').val(data[0]['TotalSoft_PG_2_10']);
        jQuery('#TotalSoft_PG_CP_50').val(data[0]['TotalSoft_PG_2_11']);
        jQuery('#TotalSoft_PG_CP_51').val(data[0]['TotalSoft_PG_2_12']);
        jQuery('#TotalSoft_PG_CP_52').val(data[0]['TotalSoft_PG_2_13']);
        jQuery('#TotalSoft_PG_CP_53').val(data[0]['TotalSoft_PG_2_14']);
        jQuery('#TotalSoft_PG_CP_54').val(data[0]['TotalSoft_PG_2_15']);
        jQuery('#TotalSoft_PG_CP_55').val(data[0]['TotalSoft_PG_2_16']);
        jQuery('#TotalSoft_PG_CP_56').val(data[0]['TotalSoft_PG_2_17']);
        jQuery('#TotalSoft_PG_CP_57').attr('checked', data[0]['TotalSoft_PG_2_18']);
        jQuery('#TotalSoft_PG_CP_58').val(data[0]['TotalSoft_PG_2_19']);
        jQuery('#TotalSoft_PG_CP_59').val(data[0]['TotalSoft_PG_2_20']);
        jQuery('#TotalSoft_PG_CP_60').val(data[0]['TotalSoft_PG_2_21']);
        jQuery('#TotalSoft_PG_CP_61').val(data[0]['TotalSoft_PG_2_22']);
        jQuery('#TotalSoft_PG_CP_62').val(data[0]['TotalSoft_PG_2_23']);
        jQuery('#TotalSoft_PG_CP_63').val(data[0]['TotalSoft_PG_2_24']);
        jQuery('#TotalSoft_PG_CP_64').val(data[0]['TotalSoft_PG_2_25']);
        jQuery('#TotalSoft_PG_CP_65').val(data[0]['TotalSoft_PG_2_26']);
        jQuery('#TotalSoft_PG_CP_66').val(data[0]['TotalSoft_PG_2_27']);
        jQuery('#TotalSoft_PG_CP_67').val(data[0]['TotalSoft_PG_2_28']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_4').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_4').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('4', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Slider Portfolio') {
        if (data[0]['TotalSoft_PG_2_01'] == 'true') {
          data[0]['TotalSoft_PG_2_01'] = true;
        } else {
          data[0]['TotalSoft_PG_2_01'] = false;
        }
        if (data[0]['TotalSoft_PG_2_08'] == 'true') {
          data[0]['TotalSoft_PG_2_08'] = true;
        } else {
          data[0]['TotalSoft_PG_2_08'] = false;
        }
        jQuery('#TotalSoft_PG_SP_40').attr('checked', data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_SP_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_SP_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_SP_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_SP_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_SP_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_SP_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_SP_47').attr('checked', data[0]['TotalSoft_PG_2_08']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_5').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_5').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('5', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Gallery Album Animation') {
        jQuery('#TotalSoft_PG_GA_40').val(data[0]['TotalSoft_PG_2_01']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_6').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_6').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('6', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Portfolio / Hover Effects') {
        jQuery('#TotalSoft_PG_PH_40').val(data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_PH_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_PH_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_PH_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_PH_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_PH_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_PH_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_PH_47').val(data[0]['TotalSoft_PG_2_08']);
        jQuery('#TotalSoft_PG_PH_48').val(data[0]['TotalSoft_PG_2_09']);
        jQuery('#TotalSoft_PG_PH_49').val(data[0]['TotalSoft_PG_2_10']);
        jQuery('#TotalSoft_PG_PH_50').val(data[0]['TotalSoft_PG_2_11']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_7').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_7').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('7', 'GO');
      } else if (data[0]['TotalSoftPortfolio_SetType'] == 'Lightbox Gallery') {
        jQuery('#TotalSoft_PG_LG_40').val(data[0]['TotalSoft_PG_2_01']);
        jQuery('#TotalSoft_PG_LG_41').val(data[0]['TotalSoft_PG_2_02']);
        jQuery('#TotalSoft_PG_LG_42').val(data[0]['TotalSoft_PG_2_03']);
        jQuery('#TotalSoft_PG_LG_43').val(data[0]['TotalSoft_PG_2_04']);
        jQuery('#TotalSoft_PG_LG_44').val(data[0]['TotalSoft_PG_2_05']);
        jQuery('#TotalSoft_PG_LG_45').val(data[0]['TotalSoft_PG_2_06']);
        jQuery('#TotalSoft_PG_LG_46').val(data[0]['TotalSoft_PG_2_07']);
        jQuery('#TotalSoft_PG_LG_47').val(data[0]['TotalSoft_PG_2_08']);
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_8').css('display', 'block');
        }, 500)
        setTimeout(function () {
          jQuery('#Total_Soft_Port_AMSetDiv_8').animate({ 'opacity': 1 }, 500);
        }, 600)
        TS_Port_TM_But('8', 'GO');
      }
      jQuery('.Total_Soft_Portfolio_AMD2').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_PortfolioTMMTable').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_PortfolioTMOTable').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_Portfolio_Save_Option').animate({ 'opacity': 0 }, 500);
      jQuery('.Total_Soft_Portfolio_Update_Option').animate({ 'opacity': 1 }, 500);
      setTimeout(function () {
        jQuery('.Total_Soft_Portfolio_AMD2').css('display', 'none');
        jQuery('.Total_Soft_PortfolioTMMTable').css('display', 'none');
        jQuery('.Total_Soft_PortfolioTMOTable').css('display', 'none');
        jQuery('.Total_Soft_Portfolio_Save_Option').css('display', 'none');
        jQuery('.Total_Soft_Portfolio_Update_Option').css('display', 'block');
        jQuery('.Total_Soft_Portfolio_AMD3').css('display', 'block');
        jQuery('#Total_Soft_Port_AMSet_Table').css('display', 'block');
      }, 300)
      setTimeout(function () {
        jQuery('.Total_Soft_Portfolio_AMD3').animate({ 'opacity': 1 }, 500);
        jQuery('#Total_Soft_Port_AMSet_Table').animate({ 'opacity': 1 }, 500);
      }, 400)
      setTimeout(function () {
        jQuery('.Total_Soft_Port_Color1').alphaColorPicker();
        jQuery('.wp-picker-holder').addClass('alpha-picker-holder');
        TotalSoft_Portfolio_Out();
      }, 500)
      setTimeout(function () {
        jQuery('.Total_Soft_Port_Loading').css('display', 'none');
      }, 800)
    }
  });
}
function TotalSoftPortfolio_Del_Option(Portfolio_OptID) {
  jQuery('#Total_Soft_PortfolioTMOTable_tr_' + Portfolio_OptID).find('.Total_Soft_Portfolio_Del_Span').addClass('Total_Soft_Portfolio_Del_Span1');
}
function TotalSoftPortfolio_Del_Opt_No(Portfolio_OptID) {
  jQuery('#Total_Soft_PortfolioTMOTable_tr_' + Portfolio_OptID).find('.Total_Soft_Portfolio_Del_Span').removeClass('Total_Soft_Portfolio_Del_Span1');
}
function TotalSoftPortfolio_Del_Opt_Yes(Portfolio_OptID) {
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolioOpt_Del', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_OptID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      location.reload();
    }
  });
}
function TotalSoftPortfolio_Clone_Option(Portfolio_OptID) {
  jQuery.ajax({
    type: 'POST',
    url: ts_pg_object.ajaxurl,
    data: {
      ts_pg_nonce_field: ts_pg_object.ts_pg_nonce_field,
      action: 'TotalSoftPortfolioOpt_Clone', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
      foobar: Portfolio_OptID, // translates into $_POST['foobar'] in PHP
    },
    beforeSend: function () {
      jQuery('.Total_Soft_Port_Loading').css('display', 'block');
    },
    success: function (response) {
      location.reload();
    }
  });
}
function TotalSoftPortfolio_Type() {
  var TotalSoftPortfolio_SetType = jQuery('#TotalSoftPortfolio_SetType').val();
  jQuery('.Total_Soft_Port_AMSetDiv').animate({ 'opacity': 0 }, 500).css('display', 'none');
  setTimeout(function () {
    if (TotalSoftPortfolio_SetType == 'Total Soft Portfolio') {
      jQuery('#Total_Soft_Port_AMSetDiv_1').css('display', 'block');
      TS_Port_TM_But('1', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Elastic Grid') {
      jQuery('#Total_Soft_Port_AMSetDiv_2').css('display', 'block');
      TS_Port_TM_But('2', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Filterable Grid') {
      jQuery('#Total_Soft_Port_AMSetDiv_3').css('display', 'block');
      TS_Port_TM_But('3', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Gallery Portfolio/Content Popup') {
      jQuery('#Total_Soft_Port_AMSetDiv_4').css('display', 'block');
      TS_Port_TM_But('4', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Slider Portfolio') {
      jQuery('#Total_Soft_Port_AMSetDiv_5').css('display', 'block');
      TS_Port_TM_But('5', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Gallery Album Animation') {
      jQuery('#Total_Soft_Port_AMSetDiv_6').css('display', 'block');
      TS_Port_TM_But('6', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Portfolio / Hover Effects') {
      jQuery('#Total_Soft_Port_AMSetDiv_7').css('display', 'block');
      TS_Port_TM_But('7', 'GO');
    } else if (TotalSoftPortfolio_SetType == 'Lightbox Gallery') {
      jQuery('#Total_Soft_Port_AMSetDiv_8').css('display', 'block');
      TS_Port_TM_But('8', 'GO');
    }
  }, 500)
  setTimeout(function () {
    if (TotalSoftPortfolio_SetType == 'Total Soft Portfolio') {
      jQuery('#Total_Soft_Port_AMSetDiv_1').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Elastic Grid') {
      jQuery('#Total_Soft_Port_AMSetDiv_2').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Filterable Grid') {
      jQuery('#Total_Soft_Port_AMSetDiv_3').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Gallery Portfolio/Content Popup') {
      jQuery('#Total_Soft_Port_AMSetDiv_4').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Slider Portfolio') {
      jQuery('#Total_Soft_Port_AMSetDiv_5').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Gallery Album Animation') {
      jQuery('#Total_Soft_Port_AMSetDiv_6').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Portfolio / Hover Effects') {
      jQuery('#Total_Soft_Port_AMSetDiv_7').animate({ 'opacity': 1 }, 500);
    } else if (TotalSoftPortfolio_SetType == 'Lightbox Gallery') {
      jQuery('#Total_Soft_Port_AMSetDiv_8').animate({ 'opacity': 1 }, 500);
    }
  }, 500)
}
function TotalSoft_Portfolio_Out() {
  jQuery('.TotalSoft_Port_Range').each(function () {
    if (jQuery(this).hasClass('TotalSoft_Port_Rangeper')) {
      jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val() + '%');
    } else if (jQuery(this).hasClass('TotalSoft_Port_Rangepx')) {
      jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val() + 'px');
    } else if (jQuery(this).hasClass('TotalSoft_Port_Rangesec')) {
      if (jQuery(this).attr('id') == 'TotalSoft_PG_EG_05' || jQuery(this).attr('id') == 'TotalSoft_PG_EG_07') {
        jQuery('#' + jQuery(this).attr('id') + '_Output').html(parseInt(parseInt(jQuery(this).val()) / 2) + 's');
      } else if (jQuery(this).attr('id') == 'TotalSoft_PG_SP_14') {
        jQuery('#' + jQuery(this).attr('id') + '_Output').html(parseInt(parseInt(jQuery(this).val()) / 10) + 's');
      } else {
        jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val() + 's');
      }
    } else {
      jQuery('#' + jQuery(this).attr('id') + '_Output').html(jQuery(this).val());
    }
  })
}
