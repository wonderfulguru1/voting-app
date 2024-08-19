<template>
	<div class="col-md-5 col-md-offset-3">
		<nav id="nav" class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header"><button type="button" data-toggle="collapse" data-target="#myNavbar"
						class="navbar-toggle"><span class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span></button> <a href="/" class="navbar-brand"><img
							src="/images/logo.png" class="logo" /></a></div>
				<div id="myNavbar" class="collapse navbar-collapse">
					<ul class="nav navbar-right navbar-nav">
						<li class="router-link-exact-active active"><a href="/login">Vote</a></li>
						<li><a href="/dashboard">Final Results</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="text-center mt-4">
			<h1 class="h2">Welcome back!</h1>
			<p class="lead">
				Please enter the token sent to you by sms and your membership ID
			</p>
		</div>
		<div class="panel panel-default">


			<div class="panel-body">
				<form @submit.prevent="login" id="login_form">

					<div class="form-group">
						<label for="email">Member ID</label>
						<input type="text" name="student_id" class="form-control" required />
					</div>

					<div class="form-group">
						<input type="submit" class="btn btn-primary form-control" value="Login" />
					</div>

				</form>
			</div>

		</div>
	</div>
</template>

<script>
export default {

	data: function () {
		return {
			loading: false
		}
	},

	created: function () {
		if (this.util.isLogin())
			//return this.$router.push({name:'Admin Home'})
			this.util.setTitle('Voter Login');
	},

	methods: {
		login: function () {
			if (this.loading) return;

			let vm = this;

			this.startLoading();

			axios.post(config.API + 'voter/login', $('#login_form').serialize())
				.then(response => {
					vm.stopLoading();
					if (this.util.showResult(response, 'success')) {
						localStorage['Access Token'] = `Bearer ${response.data.token}`;
						this.util.setAuthorization();
						vm.$router.push({ name: 'Voter Home' });
					}
				})
				.catch(error => {
					vm.stopLoading();
					this.util.showResult(error, 'error');
				})

		},

		startLoading: function () {
			this.util.notify('Logging in', 'loading');
			this.loading = true;
		},

		stopLoading: function () {
			$.notifyClose();
			this.loading = false;
		}
	},

}
</script>

<style>
body {
	padding: 50px 5px;
}
</style>