<?php
// =================================================================================
//
// SQL för skapande av användare och grupper
// 
// Skapad av: Benny Henrysson
//

// Hämtar tabellnamn
$tableUser = DBT_User;
$tableGroup = DBT_Group;
$tableGroupMember = DBT_GroupMember;
$tableTags = DBT_Tags;
$tableComment = DBT_Comment;
$tablePost = DBT_Post;

// Hämtar namn för lagrade procedurer, funktioner och triggers

// Skapar query
$query = <<<EOD
DROP TABLE IF EXISTS {$tableTags};
DROP TABLE IF EXISTS {$tableComment};
DROP TABLE IF EXISTS {$tablePost};

--
-- Table for the Blogposts
--
CREATE TABLE {$tablePost} (

  -- Primary key(s)
  idPost INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

  -- Foreign key(s)
  idAuthor INT,
  FOREIGN KEY (idAuthor) REFERENCES {$tableUser}(idUser),
  
  -- Attributes
  titlePost CHAR(100),
  textPost TEXT,
  datePost DATETIME
);

--
-- Table for the comments
--
CREATE TABLE {$tableComment} (

  -- Primary key(s)
  idComment INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

  -- Foreign key(s)
  idPostComment INT,
  FOREIGN KEY (idPostComment) REFERENCES {$tablePost}(idPost),

  -- Attributes
  titleComment CHAR(100),
  textComment TEXT,
  authorComment CHAR(100),
  dateComment DATETIME,
  emailComment CHAR(100) NOT NULL
);

--
-- Table for tags
--
CREATE TABLE {$tableTags} (

  -- Primary key(s)
  idTag INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

  -- Foreign key(s)
  idPostTag INT,
  FOREIGN KEY (idPostTag) REFERENCES {$tablePost}(idPost),

  -- Attributes
  titleTag CHAR(100)
);


EOD;

// -------------------------------------------------------------------------------
//
// Skapar exempelposter till bloggen. Ta bort eller kommentera bort om du inte behöver dessa
// 
$examplePost1 = <<< EOD
<p>In rutrum volutpat nisl, at laoreet tellus semper quis. Curabitur egestas vehicula malesuada. 
In felis felis, molestie a varius quis, congue et diam. Donec vitae interdum purus. Morbi commodo 
fermentum mauris, eget porta est placerat nec. Proin molestie, libero nec consectetur tempor, dui 
dolor gravida tortor, sit amet fermentum est magna vel nibh. Donec accumsan aliquet tincidunt. 
Aenean facilisis ante ultricies odio congue venenatis. Duis in diam dolor, a lacinia est.</p>
<p>Proin neque felis, imperdiet ultrices fringilla quis, adipiscing nec est. Proin condimentum ullamcorper velit, 
sed lacinia ante feugiat et. Sed vestibulum ante vel mi malesuada dignissim. Praesent tempor sagittis est, 
eu varius dui egestas vel. Fusce leo dui, suscipit ac sodales ut, tristique imperdiet lorem. Integer pretium 
sapien in arcu dictum auctor. Sed tempor nisi non velit tristique ut facilisis risus vulputate. Aliquam in 
neque ut purus gravida hendrerit. Ut sed velit tellus, quis ultrices diam.</p>
EOD;

$examplePost2 = <<< EOD
<p>Morbi ac ligula urna. In commodo lorem ac dolor fringilla condimentum. Quisque vel tincidunt arcu. 
Quisque viverra molestie lectus, ut tempus arcu consectetur vitae. Cras nibh mi, tempor ac gravida nec, 
posuere quis quam. Integer sit amet ligula sem, id tincidunt libero. Donec non erat metus, sed blandit 
est. In lacus mauris, ornare eget dictum at, faucibus nec augue. Sed aliquam metus nec tortor iaculis 
in pellentesque magna scelerisque. Nullam suscipit eleifend purus et tincidunt. Morbi mollis urna id 
velit porttitor feugiat. Sed mollis nibh et risus adipiscing sit amet aliquet magna elementum. Sed 
condimentum elit tellus, posuere luctus lorem. Aenean sit amet nisi enim, in molestie sem. Proin 
feugiat est neque. In feugiat blandit nibh ut faucibus.</p>
EOD;

$examplePost3 = <<< EOD
<p>Sed ac nisl quis eros vehicula blandit. Nam pellentesque lobortis fermentum. Etiam eget justo a mi
egestas lobortis at in nibh. Aenean quis massa diam. Cras ornare sollicitudin nisi quis ullamcorper. 
Suspendisse eget elit id odio euismod faucibus. Pellentesque ut metus augue, sagittis semper ligula.</p> 
<p>Curabitur gravida placerat pulvinar. Cum sociis natoque penatibus et magnis dis parturient montes, 
nascetur ridiculus mus. In diam nisl, suscipit eget pellentesque ac, consectetur vel ante. Pellentesque 
vestibulum vehicula lectus at volutpat. Curabitur nibh dui, luctus a ultricies eu, venenatis sed nunc.</p>
<p>Mauris nec feugiat massa. Fusce felis ligula, interdum et rhoncus et, egestas vitae odio. Integer a 
arcu sed diam interdum porta. Proin vel lacus dolor, in blandit risus. Phasellus eu purus nibh. Cras 
vehicula, purus nec tempor viverra, magna enim ullamcorper ipsum, vel consequat magna felis non metus. 
Nullam eu viverra massa.</p>
EOD;

$examplePost4 = <<< EOD
<p>Ut lacinia arcu ac lacus sollicitudin tristique. Mauris odio enim, malesuada at ullamcorper non, 
auctor ut sapien. Donec at sapien ac arcu tincidunt tincidunt eu a libero. Suspendisse tristique massa 
in lorem faucibus scelerisque ac ac nisl. Vivamus malesuada nisl vitae diam imperdiet eu faucibus ipsum commodo.</p>
EOD;

$examplePost5 = <<< EOD
<p>Phasellus nec felis justo, facilisis tristique mi. Morbi fermentum tortor ac leo rhoncus sed convallis 
ante porta. Phasellus pulvinar, velit id consequat auctor, arcu tellus suscipit est, eget congue magna 
mauris facilisis lorem. Ut vitae nisi urna. Vivamus vestibulum tristique risus vel sollicitudin.</p> 
<p>Phasellus dui est, rutrum a vestibulum non, varius nec tellus. Lorem ipsum dolor sit amet, consectetur 
adipiscing elit. Etiam diam lacus, iaculis id dictum non, porttitor feugiat tortor. Mauris mattis rutrum metus. 
Pellentesque eget nunc metus. Proin iaculis elit ac est lobortis laoreet et non felis. Aliquam odio libero, 
varius a iaculis in, lobortis id dolor. Maecenas orci mauris, suscipit sed interdum at, consectetur placerat 
neque. Nullam scelerisque, urna vel tempor sodales, ipsum nisl iaculis tortor, ut mollis lorem nisi et urna.</p>
<p>Fusce gravida, quam quis egestas condimentum, neque massa accumsan tellus, sed tempor libero lorem quis lorem. 
Integer non odio fringilla metus iaculis vulputate vitae lacinia orci. Duis in ullamcorper lectus.</p>
EOD;

$examplePost6 = <<< EOD
<p>Sed ac nisl quis eros vehicula blandit. Nam pellentesque lobortis fermentum. Etiam eget justo a mi
egestas lobortis at in nibh. Aenean quis massa diam. Cras ornare sollicitudin nisi quis ullamcorper. 
Suspendisse eget elit id odio euismod faucibus. Pellentesque ut metus augue, sagittis semper ligula.</p> 
<p>Curabitur gravida placerat pulvinar. Cum sociis natoque penatibus et magnis dis parturient montes, 
nascetur ridiculus mus. In diam nisl, suscipit eget pellentesque ac, consectetur vel ante. Pellentesque 
vestibulum vehicula lectus at volutpat. Curabitur nibh dui, luctus a ultricies eu, venenatis sed nunc.</p>
<p>Mauris nec feugiat massa. Fusce felis ligula, interdum et rhoncus et, egestas vitae odio. Integer a 
arcu sed diam interdum porta. Proin vel lacus dolor, in blandit risus. Phasellus eu purus nibh. Cras 
vehicula, purus nec tempor viverra, magna enim ullamcorper ipsum, vel consequat magna felis non metus. 
Nullam eu viverra massa.</p>
EOD;

$examplePost7 = <<< EOD
<p>Phasellus auctor mauris id odio tempus eleifend. Donec erat ipsum, vulputate ullamcorper blandit ut, 
tempor eu lorem. Phasellus justo dui, blandit et bibendum tempus, blandit vitae nunc. Nulla facilisi. 
In euismod nisl id massa varius et vehicula odio tempus. Donec dictum dui et mi feugiat pulvinar non in odio. 
Pellentesque risus orci, volutpat consectetur luctus eget, molestie ut justo. Nam quis quam nulla, 
sed ultricies nisl. Mauris magna augue, tristique at volutpat id, facilisis ut quam. Vivamus erat tellus, 
semper a vehicula eu, gravida rhoncus lorem. Donec ullamcorper tincidunt velit, ac sagittis lorem luctus eu.</p>
<p>In vel purus mauris, in tristique leo. Sed id quam enim, quis malesuada risus. Ut sagittis tincidunt facilisis. 
Cras bibendum lorem ac mi rutrum tempus. Suspendisse potenti. Praesent id risus leo, ut porttitor nisl. 
Maecenas sapien erat, ornare ac vulputate id, volutpat feugiat ante. Maecenas non lacus lectus, sed consectetur 
risus. Sed a massa justo, at tristique dui.</p>
EOD;

$examplePost8 = <<< EOD
<p>Suspendisse potenti. In eleifend varius purus, sit amet sollicitudin leo fermentum sed. Suspendisse venenatis 
nisl massa. Ut lobortis, augue ac varius volutpat, quam justo lacinia ligula, et viverra quam sem at nibh. 
Sed laoreet lobortis mollis. Vivamus dignissim consectetur dapibus. Sed dapibus mollis tempus. Donec mauris tortor, 
cursus non vulputate eget, faucibus a arcu. Sed mollis convallis porta. Sed varius sagittis mauris sed adipiscing.</p>
<p>Ut sodales, ipsum quis pharetra faucibus, enim mauris dictum nisi, vitae faucibus nibh urna nec arcu. 
Fusce pellentesque, nisi lacinia scelerisque vulputate, odio ipsum laoreet massa, eu ultricies lectus neque eget 
purus. Duis nulla nunc, dignissim molestie consectetur eget, faucibus nec tellus.</p>
EOD;

$examplePost9 = <<< EOD
<p>Donec eget mattis ipsum. Nulla sit amet felis quis felis egestas posuere. Maecenas dolor nibh, fringilla 
in viverra sit amet, pulvinar quis tortor. Vestibulum ac tellus ultricies nisi euismod viverra. Curabitur molestie 
ultrices metus, a congue mauris consectetur in. Donec tortor mauris, iaculis nec aliquam vitae, sagittis et turpis. 
Fusce pretium varius mattis.</p>
EOD;

$examplePost10 = <<< EOD
<p>Curabitur vehicula sodales augue, sit amet aliquam justo mollis ac. Etiam bibendum velit vel nunc blandit 
imperdiet. Sed nunc ante, pellentesque non lobortis nec, pellentesque vitae sem. Nullam euismod viverra volutpat. 
Pellentesque elit nisl, varius lacinia fermentum vitae, luctus in eros. Sed vel magna non lacus cursus fermentum. 
Proin dapibus, augue ac mollis viverra, nisi justo dictum diam, in consequat enim sem sit amet elit. Etiam tempus 
pretium metus vel volutpat. Proin ut ipsum at augue mollis adipiscing. </p>
<p>Aenean tincidunt diam id risus ultrices id 
rutrum velit commodo. Aenean sed erat nibh, nec venenatis metus. Ut et sollicitudin tortor. Etiam risus ante, euismod 
quis vehicula eget, viverra eu arcu. Sed pretium, felis sed mollis varius, ipsum justo vulputate velit, non 
consectetur mi neque id sem. Phasellus purus dolor, porta quis tempus vel, accumsan nec quam. Curabitur suscipit 
elit in dui lobortis ultrices. Vestibulum magna dui, congue et dictum ac, gravida ac dolor. </p>
EOD;

$examplePost11 = <<< EOD
<p>Etiam a nisi metus. Nullam suscipit dui eu orci consectetur pharetra. Duis posuere scelerisque lacus nec 
viverra. Donec id dolor non mi pellentesque interdum. Sed eget tincidunt arcu. Aenean et feugiat eros. 
Proin luctus aliquet malesuada. Proin molestie gravida diam, non fermentum nisi luctus vel. Fusce at eros eros.</p>
<p>Ut vel leo non orci hendrerit convallis. Mauris feugiat lobortis iaculis. Mauris volutpat mauris neque, in 
venenatis lorem. Quisque ac mauris eros, at placerat erat. Etiam eu mauris quis leo congue dapibus et a justo. </p>
EOD;

$examplePost12 = <<< EOD
<p>Etiam eget magna a magna adipiscing scelerisque. Cras molestie urna a mi auctor euismod. Etiam sed odio ipsum. 
Aenean commodo elementum neque at tincidunt. Cras porta lorem vel quam suscipit eu condimentum felis eleifend. 
Sed fermentum odio sed massa condimentum condimentum. Sed suscipit nisl vel turpis blandit blandit. Nullam nulla 
risus, dapibus eu varius dictum, adipiscing in urna. Nunc justo tellus, imperdiet quis blandit at, imperdiet id 
quam. In facilisis, leo eu tincidunt ullamcorper, nibh felis iaculis ipsum, sit amet venenatis sem sem id risus.</p>
EOD;

$query .= <<<EOD
--
-- Insert example posts and comments
--
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'benny'), 'Då var bloggen startad', '{$examplePost1}', '2009-05-10 15:09');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'benny'), 'Mat och nöje på en fredagkväll', '{$examplePost2}', '2010-06-14 18:39');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'benny'), 'En vanlig dag på jobbet', '{$examplePost3}', '2010-09-23 03:03');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'benny'), 'Jag kan leva utan mat', '{$examplePost4}', '2010-12-08 12:15');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'Första inlägget!', '{$examplePost5}', '2010-09-03 14:39');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'Vilodag', '{$examplePost6}', '2010-10-01 15:34');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'Dagens träning!', '{$examplePost7}', '2010-10-12 16:27');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'Reps och Set', '{$examplePost8}', '2010-10-22 18:24');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'Dagens pass', '{$examplePost9}', '2010-12-06 11:02');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'bosse'), 'Skäms på er!', '{$examplePost10}', '2010-12-08 13:11');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'bosse'), 'Kaffet slut igen!', '{$examplePost11}', '2010-11-18 19:45');
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'tanja'), 'Recension: Roquefort och Stilton', '{$examplePost12}', '2010-11-07 23:16');

INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (1, 'Äntligen', 'Som vi har väntat', 'Räkan', '2009-05-10 15:15', 'Shrimpy@hotmail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (4, 'Jag med', 'Jag har även tänkt i dessa banor vid ett flertal tillfällen.', 'Inge Stor', '2010-12-08 12:19', 'inge.stor@mail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (8, 'Jaha', 'Nu förstår jag, tack för klargörandet.', 'J Ausonius', '2010-10-23 09:10', 'lonelygunner@hotmail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (8, 'Men...', 'Vad innebär ett reps?', 'Jackie A', '2010-10-24 03:16', 'jackie.a@aol.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (8, 'Till Jackie', 'Googla.', 'M Alm', '2010-10-24 15:17', 'happycamper@gmail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (11, 'Du', 'Det var inte jag!', 'henkap', '2010-11-19 19:17', 'henkobenko@hotmail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (11, 'Okej', 'Men slut e de lik förbannat', 'bosse', '2010-11-20 09:41', 'bo.svensson@hotmail.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (12, 'Nice!', 'Kanske dags att ta sig till ostdisken.', 'Johnny Cash', '2010-11-07 23:22', 'maninblack@yahoo.com');
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES (12, 'Hej', 'Kan även rekommendera en Havarti.', 'Carl Jan', '2010-11-07 23:34', 'cj@gmail.com');

INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (2, 'mat');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (2, 'fest');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (4, 'mat');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (4, 'livsstil');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (7, 'träning');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (7, 'gym');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (8, 'träning');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (8, 'livsstil');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (9, 'träning');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (9, 'gym');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (10, 'livsstil');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (10, 'arbete');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (11, 'arbete');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (12, 'mat');
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES (12, 'ost');
EOD;

?>
