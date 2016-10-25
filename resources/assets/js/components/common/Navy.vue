<template>
   <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
         <!-- Brand and toggle get grouped for better mobile display -->
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Title</a>
         </div>
   
         <!-- Collect the nav links, forms, and other content for toggling -->
         <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
               <li class="active"><a href="#">Link</a></li>
               <li><a href="#">Link</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
               <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
               </div>
               <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
               <li><a href="#">Link</a></li>
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"> {{ username }} <b class="caret"></b></a>
                  <ul class="dropdown-menu" v-if="isLoggedIn"  >

                     <li><a :href="linkLogout">Logout</a></li>

                  </ul>
                  <ul class="dropdown-menu" v-else>

                     <li><a :href="linkRegister">Register</a></li>
                     <li><a :href="linkLogin">Login</a></li>

                  </ul>
               </li>
            </ul>
         </div><!-- /.navbar-collapse -->
      </div>
   </nav>
</template>

<script>
   var isLoggedIn = $("meta[name=login-status]").attr('content');

   export default{
      data() {
         return {
            linkRegister: '/register',
            linkLogin: '/login',
            linkLogout: '/logout',
            isLoggedIn: isLoggedIn,
            username: ''
         }
      },

      created: function() {
         this.$http.get('/get_user_logged').then(function (res) {
            this.username = res.body.name
         });
      }
   }
</script>
