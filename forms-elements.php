<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  error_reporting(E_ALL);

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  require 'include/auth.php';
  require 'db/dbconnect.php';
  $success = '';
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = isset($_POST['request']) ? $_POST['request'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

  /* ============================
     CHECK EXISTING RECORD
  ============================ */

  $stmt = $conn->prepare("

    SELECT ojt_id
    FROM ojt_form_details
    WHERE email=?
    LIMIT 1

  ");

  $stmt->bind_param(
    "s",
    $email
  );

  $stmt->execute();

  $stmt->bind_result(
    $ojt_id
  );

  $stmt->fetch();

  $existing = [

    'ojt_id' => $ojt_id

  ];

    /* ============================
       CREATE BLANK RECORD
    ============================ */

    if (!$existing) {
      $stmt = $conn->prepare("
      INSERT INTO ojt_form_details (email)
      VALUES (?)
      ");

      $stmt->bind_param(
        "s",
        $email
      );
      $stmt->execute();
      $ojt_id = $conn->insert_id;
    }
    else {
      $ojt_id = $existing['ojt_id'];
    }

    /* ============================
       STUDENT FORM
    ============================ */

    if ($request == 'student') {

      $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
      $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
      $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
      $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
      $province = mysqli_real_escape_string($conn, $_POST['province']);
      $dob = mysqli_real_escape_string($conn, $_POST['dob']);
      $birthplace = mysqli_real_escape_string($conn, $_POST['birthplace']);
      $height = mysqli_real_escape_string($conn, $_POST['height']);
      $weight = mysqli_real_escape_string($conn, $_POST['weight']);
      $religion = mysqli_real_escape_string($conn, $_POST['religion']);
      $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
      $gender = mysqli_real_escape_string($conn, $_POST['gender']);
      $citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
      $contactno = mysqli_real_escape_string($conn, $_POST['contactno']);
      $dialect = mysqli_real_escape_string($conn, $_POST['dialect']);
      $course = mysqli_real_escape_string($conn, $_POST['course']);
      $major = mysqli_real_escape_string($conn, $_POST['major']);
      $datestart = mysqli_real_escape_string($conn, $_POST['datestart']);
      $ojthours = mysqli_real_escape_string($conn, $_POST['ojthours']);
      $email = mysqli_real_escape_string($conn, $email);

      $query = "

        UPDATE ojt_form_details

        SET

          lastname='$lastname',
          firstname='$firstname',
          middlename='$middlename',
          municipality='$municipality',
          province='$province',
          dob='$dob',
          birthplace='$birthplace',
          height='$height',
          weight='$weight',
          religion='$religion',
          marital_status='$marital_status',
          gender='$gender',
          citizenship='$citizenship',
          contactno='$contactno',
          dialect='$dialect',
          course='$course',
          major='$major',
          datestart='$datestart',
          ojthours='$ojthours'

        WHERE email='$email'

      ";
      $stmt->close();
      // $stmt= mysqli_query($conn, $query);
      if (mysqli_query($conn, $query)) {
        # code...
        if (mysqli_affected_rows($conn)==1) {
          # code...
          header('location: forms-elements.php');

          exit();
        }else{
          echo "Not Saved";
        }
      }else{
        echo mysqli_error($conn);
      }


    }

    /* ============================
       PARENT FORM
    ============================ */

    if ($request == 'parent') {

      $father = mysqli_real_escape_string($conn, $_POST['father']);
      $fatheroccupation = mysqli_real_escape_string($conn, $_POST['fatheroccupation']);
      $fatheraddress = mysqli_real_escape_string($conn, $_POST['fatheraddress']);

      $mother = mysqli_real_escape_string($conn, $_POST['mother']);
      $motheroccupation = mysqli_real_escape_string($conn, $_POST['motheroccupation']);
      $motheraddress = mysqli_real_escape_string($conn, $_POST['motheraddress']);

      $guardian = mysqli_real_escape_string($conn, $_POST['guardian']);
      $guardianaddress = mysqli_real_escape_string($conn, $_POST['guardianaddress']);


      $query = "
        UPDATE ojt_form_details
        SET
          father='$father',
          fatheroccupation='$fatheroccupation',
          fatheraddress='$fatheraddress',
          mother='$mother',
          motheroccupation='$motheroccupation',
          motheraddress='$motheraddress',
          guardian='$guardian',
          guardianaddress='$guardianaddress'
        WHERE email='$email'
      ";
      $stmt->close();
      mysqli_query($conn, $query);

      header('location: forms-elements.php');
      exit();

    }

    /* ============================
       AGENCY FORM
    ============================ */

    if ($request == 'agency') {

      $agency = mysqli_real_escape_string($conn, $_POST['agency']);
      $representative = mysqli_real_escape_string($conn, $_POST['representative']);
      $position = mysqli_real_escape_string($conn, $_POST['position']);
      $agencycontact = mysqli_real_escape_string($conn, isset($_POST['agencycontact']) ? $_POST['agencycontact'] : '');

      $agencyaddress1 = mysqli_real_escape_string($conn, $_POST['agencyaddress1']);
      $agencyaddress2 = mysqli_real_escape_string($conn, $_POST['agencyaddress2']);
      $agencyaddress3 = mysqli_real_escape_string($conn, $_POST['agencyaddress3']);
      $agencyaddress4 = mysqli_real_escape_string($conn, $_POST['agencyaddress4']);
      $agencyaddress5 = mysqli_real_escape_string($conn, $_POST['agencyaddress5']);

      

      $query = "
        UPDATE ojt_form_details
        SET
          agency='$agency',
          representative='$representative',
          rep_position='$position',
          agencycontact='$agencycontact',
          agencyaddress1='$agencyaddress1',
          agencyaddress2='$agencyaddress2',
          agencyaddress3='$agencyaddress3',
          agencyaddress4='$agencyaddress4',
          agencyaddress5='$agencyaddress5'
        WHERE email='$email'
      ";
      $stmt->close();
      if (mysqli_query($conn, $query)) {
        # code...
        // echo mysqli_affected_rows($conn);
        header('location: forms-elements.php');
        exit();
      }else{
        echo mysqli_error($conn);
        exit();
      }


    }

  }



  /* ============================
     GET OJT ID
  ============================ */

  $ojt_id = isset($_GET['ojt_id']) ? $_GET['ojt_id'] : 0;

  /* ============================
     DEFAULT FORM ARRAY
  ============================ */

  $form = array();

  /* ============================
     SEARCH EXISTING RECORD
  ============================ */

  // var_dump($_SESSION);
  // exit();

  // $stmt = $conn->prepare("
  //   SELECT *
  //   FROM ojt_form_details
  //   WHERE email=?

  //   LIMIT 1
  // ");

  // $stmt->bind_param(
  //   "s",
  //   $_SESSION['email']
  // );

  // $stmt->execute();
  // $result = $stmt->get_result();

  // $form = $result->fetch_assoc();
  // print_r($form);

$stmt = $conn->prepare("

  SELECT
    ojt_id,
    user_id,
    lastname,
    firstname,
    middlename,
    municipality,
    province,
    dob,
    birthplace,
    height,
    weight,
    religion,
    marital_status,
    gender,
    citizenship,
    contactno,
    email,
    dialect,
    course,
    major,
    datestart,
    ojthours,
    father,
    fatheroccupation,
    fatheraddress,
    mother,
    motheroccupation,
    motheraddress,
    guardian,
    guardianaddress,
    agency,
    representative,
    rep_position,
    agencycontact,
    agencyaddress1,
    agencyaddress2,
    agencyaddress3,
    agencyaddress4,
    agencyaddress5,
    created_at,
    updated_at

  FROM ojt_form_details

  WHERE email=?

  LIMIT 1

");

$stmt->bind_param(
  "s",
  $_SESSION['email']
);

$stmt->execute();

$stmt->bind_result(

  $ojt_id,
  $user_id,
  $lastname,
  $firstname,
  $middlename,
  $municipality,
  $province,
  $dob,
  $birthplace,
  $height,
  $weight,
  $religion,
  $marital_status,
  $gender,
  $citizenship,
  $contactno,
  $email,
  $dialect,
  $course,
  $major,
  $datestart,
  $ojthours,
  $father,
  $fatheroccupation,
  $fatheraddress,
  $mother,
  $motheroccupation,
  $motheraddress,
  $guardian,
  $guardianaddress,
  $agency,
  $agencyrepresentative,
  $rep_position,
  $agencycontact,
  $agencyaddress1,
  $agencyaddress2,
  $agencyaddress3,
  $agencyaddress4,
  $agencyaddress5,
  $created_at,
  $updated_at

);

$stmt->fetch();

$form = [

  'ojt_id' => $ojt_id,
  'user_id' => $user_id,
  'lastname' => $lastname,
  'firstname' => $firstname,
  'middlename' => $middlename,
  'municipality' => $municipality,
  'province' => $province,
  'dob' => $dob,
  'birthplace' => $birthplace,
  'height' => $height,
  'weight' => $weight,
  'religion' => $religion,
  'marital_status' => $marital_status,
  'gender' => $gender,
  'citizenship' => $citizenship,
  'contactno' => $contactno,
  'email' => $email,
  'dialect' => $dialect,
  'course' => $course,
  'major' => $major,
  'datestart' => $datestart,
  'ojthours' => $ojthours,
  'father' => $father,
  'fatheroccupation' => $fatheroccupation,
  'fatheraddress' => $fatheraddress,
  'mother' => $mother,
  'motheroccupation' => $motheroccupation,
  'motheraddress' => $motheraddress,
  'guardian' => $guardian,
  'guardianaddress' => $guardianaddress,
  'agency' => $agency,
  'representative' => $agencyrepresentative,
  'rep_position' => $rep_position,
  'agencycontact' => $agencycontact,
  'agencyaddress1' => $agencyaddress1,
  'agencyaddress2' => $agencyaddress2,
  'agencyaddress3' => $agencyaddress3,
  'agencyaddress4' => $agencyaddress4,
  'agencyaddress5' => $agencyaddress5,
  'created_at' => $created_at,
  'updated_at' => $updated_at

];
$stmt->close();


  

?>
<?php require('header.php'); ?>
  <!-- ======= Sidebar ======= -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>OJT Form Elements</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Forms</li>
          <li class="breadcrumb-item active">Elements</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Student Information</h5>

              <!-- General Form Elements -->
              <form method="POST" action="forms-elements.php">

                <input type="hidden"
                name="ojt_id"
                value="<?php echo isset($ojt_id) ? $ojt_id : 0; ?>">

                <div class="row mb-3">

                  <div class="col-sm-4">
                    <label class="form-label">Last Name</label>

                    <input type="text"
                    class="form-control"
                    name="lastname"

                    value="<?php

                    echo isset($form['lastname'])
                    ? $form['lastname']
                    : $_SESSION['last_name'];

                    ?>">
                  </div>

                  <div class="col-sm-4">
                    <label class="form-label">First Name</label>

                    <input type="text"
                    class="form-control"
                    name="firstname"

                    value="<?php

                    echo isset($form['firstname'])
                    ? $form['firstname']
                    : $_SESSION['first_name'];

                    ?>">
                  </div>

                  <div class="col-sm-4">
                    <label class="form-label">Middle Name</label>

                    <input type="text"
                    class="form-control"
                    name="middlename"

                    value="<?php

                    echo isset($form['middlename'])
                    ? $form['middlename']
                    : '';

                    ?>">
                  </div>

                </div>


                <div class="row mb-3">

                  <div class="col-sm-6">
                    <label class="form-label">
                    Municipality / City
                    </label>

                    <input type="text"
                    class="form-control"
                    name="municipality"

                    value="<?php

                    echo isset($form['municipality'])
                    ? $form['municipality']
                    : '';

                    ?>">
                  </div>

                  <div class="col-sm-6">
                    <label class="form-label">Province</label>

                    <input type="text"
                    class="form-control"
                    name="province"

                    value="<?php

                    echo isset($form['province'])
                    ? $form['province']
                    : 'Sultan Kudarat';

                    ?>">
                  </div>

                </div>


                <div class="row mb-3">

                  <div class="col-sm-3">
                    <label class="form-label">Birthdate</label>

                    <input type="date"
                    class="form-control"
                    name="dob"

                    value="<?php

                    echo isset($form['dob'])
                    ? $form['dob']
                    : '';

                    ?>">
                  </div>

                  <div class="col-sm-9">
                    <label class="form-label">
                    Birth Place
                    </label>

                    <input type="text"
                    class="form-control"
                    name="birthplace"

                    value="<?php

                    echo isset($form['birthplace'])
                    ? $form['birthplace']
                    : '';

                    ?>">
                  </div>

                </div>


                <div class="row mb-3">

                  <div class="col-sm-4">
                    <label class="form-label">
                    Height (cm)
                    </label>

                    <input type="number"
                    class="form-control"
                    name="height"

                    value="<?php

                    echo isset($form['height'])
                    ? $form['height']
                    : '';

                    ?>">
                  </div>

                  <div class="col-sm-4">
                    <label class="form-label">
                    Weight (kg)
                    </label>

                    <input type="number"
                    class="form-control"
                    name="weight"

                    value="<?php

                    echo isset($form['weight'])
                    ? $form['weight']
                    : '';

                    ?>">
                  </div>

                  <div class="col-sm-4">
                    <label class="form-label">
                    Religion
                    </label>

                    <input type="text"
                    class="form-control"
                    name="religion"

                    value="<?php

                    echo isset($form['religion'])
                    ? $form['religion']
                    : '';

                    ?>">
                  </div>

                </div>


                <div class="row mb-3">

                  <div class="col-sm-3">

                    <label class="form-label">
                    Marital Status
                    </label>

                    <select class="form-select"
                    name="marital_status">

                      <option value="">
                      Select
                      </option>

                      <option value="Single"

                      <?php

                      if (
                        isset($form['marital_status'])
                        &&
                        $form['marital_status'] == 'Single'
                      ) {

                        echo 'selected';

                      }

                      ?>

                      >

                      Single

                      </option>

                      <option value="Married"

                      <?php

                      if (
                        isset($form['marital_status'])
                        &&
                        $form['marital_status'] == 'Married'
                      ) {

                        echo 'selected';

                      }

                      ?>

                      >

                      Married

                      </option>

                    </select>

                  </div>


                  <div class="col-sm-2">

                    <label class="form-label">
                    Gender
                    </label>

                    <select class="form-select"
                    name="gender">

                      <option value="">
                      Select
                      </option>

                      <option value="Male"

                      <?php

                      if (
                        isset($form['gender'])
                        &&
                        $form['gender'] == 'Male'
                      ) {

                        echo 'selected';

                      }

                      ?>

                      >

                      Male

                      </option>

                      <option value="Female"

                      <?php

                      if (
                        isset($form['gender'])
                        &&
                        $form['gender'] == 'Female'
                      ) {

                        echo 'selected';

                      }

                      ?>

                      >

                      Female

                      </option>

                    </select>

                  </div>


                  <div class="col-sm-3">

                    <label class="form-label">
                    Citizenship
                    </label>

                    <input type="text"
                    class="form-control"
                    name="citizenship"

                    value="<?php

                    echo isset($form['citizenship'])
                    ? $form['citizenship']
                    : '';

                    ?>">

                  </div>


                  <div class="col-sm-4">

                    <label class="form-label">
                    Contact No.
                    </label>

                    <input type="text"
                    class="form-control"
                    name="contactno"

                    value="<?php

                    echo isset($form['contactno'])
                    ? $form['contactno']
                    : '';

                    ?>">

                  </div>

                </div>


                <div class="row mb-3">

                  <div class="col-sm-6">

                    <label class="form-label">
                    Email
                    </label>

                    <input type="text"
                    class="form-control"
                    name="email"

                    value="<?php

                    echo isset($form['email'])
                    ? $form['email']
                    : $_SESSION['email'];

                    ?>" readonly>

                  </div>


                  <div class="col-sm-6">

                    <label class="form-label">
                    Dialect
                    </label>

                    <input type="text"
                    class="form-control"
                    name="dialect"

                    value="<?php

                    echo isset($form['dialect'])
                    ? $form['dialect']
                    : '';

                    ?>">

                  </div>

                </div>
                <div class="row mb-3">

  <div class="col-sm-6">

    <label class="form-label">
    Course
    </label>

    <select class="form-select"
    name="course">

      <option value="">
      Select
      </option>

      <option value="Bachelor of Science in Civil Engineering"

      <?php

      if (
        isset($form['course'])
        &&
        $form['course'] == 'Bachelor of Science in Civil Engineering'
      ) {

        echo 'selected';

      }

      ?>

      >

      Bachelor of Science in Civil Engineering

      </option>

      <option value="Bachelor of Science in Computer Engineering"

      <?php

      if (
        isset($form['course'])
        &&
        $form['course'] == 'Bachelor of Science in Computer Engineering'
      ) {

        echo 'selected';

      }

      ?>

      >

      Bachelor of Science in Computer Engineering

      </option>

      <option value="Bachelor of Science in Electronics Engineering"

      <?php

      if (
        isset($form['course'])
        &&
        $form['course'] == 'Bachelor of Science in Electronics Engineering'
      ) {

        echo 'selected';

      }

      ?>

      >

      Bachelor of Science in Electronics Engineering

      </option>

    </select>

  </div>

  <div class="col-sm-6">

    <label class="form-label">
    Major
    </label>

    <input type="text"
    class="form-control"
    name="major"

    value="<?php

    echo isset($form['major'])
    ? $form['major']
    : '';

    ?>">

  </div>

</div>


<div class="row mb-3">

  <div class="col-sm-3">

    <label class="form-label">
    Date of Start
    </label>

    <input type="date"
    class="form-control"
    name="datestart"

    value="<?php

    echo isset($form['datestart'])
    ? $form['datestart']
    : '';

    ?>">

  </div>

  <div class="col-sm-3">

    <label class="form-label">
    OJT No. of Hours
    </label>

    <input type="number"
    class="form-control"
    name="ojthours"
    readonly

    value="<?php

    echo isset($form['ojthours'])
    ? $form['ojthours']
    : '240';

    ?>">

  </div>

</div>



                <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Submit Button</label> -->
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" name="request" value="student">Submit Form</button>
                  </div>
                </div>
              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>


        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Father's Information</h5>
              <form method="POST" action="forms-elements.php">
                <input type="hidden" name="ojt_id" value="<?php echo isset($ojt_id) ? $ojt_id : 0; ?>">
                <!-- Parent Details -->
                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Father's Name</label>
                    <input type="text" class="form-control" name="father" placeholder="" value="<?php echo isset($form['father']) ? $form['father'] : ''; ?>">
                  </div>

                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Father's Occupation</label>
                    <input type="text" class="form-control" name="fatheroccupation" placeholder="" value="<?php echo isset($form['fatheroccupation']) ? $form['fatheroccupation'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address</label>
                    <input type="text" class="form-control" name="fatheraddress" placeholder="" value="<?php echo isset($form['fatheraddress']) ? $form['fatheraddress'] : ''; ?>">
                  </div>
                </div>

                <h5 class="card-title">Mother's Information</h5>

                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control" name="mother" placeholder="" value="<?php echo isset($form['mother']) ? $form['mother'] : ''; ?>">
                  </div>

                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Mother's Occupation</label>
                    <input type="text" class="form-control" name="motheroccupation" placeholder="" value="<?php echo isset($form['motheroccupation']) ? $form['motheroccupation'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address</label>
                    <input type="text" class="form-control" name="motheraddress" placeholder="" value="<?php echo isset($form['motheraddress']) ? $form['motheraddress'] : ''; ?>">
                  </div>
                </div>

                <h5 class="card-title">Guardians's Information</h5>

                <div class="row mb-3">
                  <div class="col-sm-4">
                    <label for="middlename" class="form-label">Guardian's Name</label>
                    <input type="text" class="form-control" name="guardian" placeholder="" value="<?php echo isset($form['guardian']) ? $form['guardian'] : ''; ?>">
                  </div>

                  <div class="col-sm-8">
                    <label for="middlename" class="form-label">Guardian's Address</label>
                    <input type="text" class="form-control" name="guardianaddress" placeholder="" value="<?php echo isset($form['guardianaddress']) ? $form['guardianaddress'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Submit Button</label> -->
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" name="request" value="parent">Submit Form</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>


        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Agency's Information</h5>
              <form method="POST" action="forms-elements.php">
                <input type="hidden" name="ojt_id" value="<?php echo isset($ojt_id) ? $ojt_id : 0; ?>">

                <!-- Parent Details -->
                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Name of Agency</label>
                    <input type="text" class="form-control" name="agency" placeholder="" value="<?php echo isset($form['agency']) ? $form['agency'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Name of Representative</label>
                    <input type="text" class="form-control" name="representative" placeholder="" value="<?php echo isset($form['representative']) ? $form['representative'] : ''; ?>">
                  </div>

                  <div class="col-sm-6">
                    <label for="middlename" class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" placeholder="" value="<?php echo isset($form['rep_position']) ? $form['rep_position'] : ''; ?>">
                  </div>
                </div>
                <div class="row mb-3">

                  <div class="col-sm-6">

                    <label class="form-label">
                      Contact Number
                    </label>

                    <input
                    type="text"
                    class="form-control"
                    name="agencycontact"
                    value="<?php echo isset($form['agencycontact']) ? $form['agencycontact'] : ''; ?>">

                  </div>

                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address Line 1</label>
                    <input type="text" class="form-control" name="agencyaddress1" placeholder="" value="<?php echo isset($form['agencyaddress1']) ? $form['agencyaddress1'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address Line 2</label>
                    <input type="text" class="form-control" name="agencyaddress2" placeholder="" value="<?php echo isset($form['agencyaddress2']) ? $form['agencyaddress2'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address Line 3</label>
                    <input type="text" class="form-control" name="agencyaddress3" placeholder="" value="<?php echo isset($form['agencyaddress3']) ? $form['agencyaddress3'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address Line 4</label>
                    <input type="text" class="form-control" name="agencyaddress4" placeholder="" value="<?php echo isset($form['agencyaddress4']) ? $form['agencyaddress4'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-sm-12">
                    <label for="middlename" class="form-label">Address Line 5</label>
                    <input type="text" class="form-control" name="agencyaddress5" placeholder="" value="<?php echo isset($form['agencyaddress5']) ? $form['agencyaddress5'] : ''; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Submit Button</label> -->
                  <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" name="request" value="agency">Submit Form</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>


      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>