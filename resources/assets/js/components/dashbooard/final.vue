<template>
	<div>
		<nav id="nav" class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header"><button type="button" data-toggle="collapse" data-target="#myNavbar"
						class="navbar-toggle"><span class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span></button> <a href="/" class="navbar-brand"><img
							src="/images/logo.png" class="logo" /></a></div>
				<div id="myNavbar" class="collapse navbar-collapse">
					<ul class="nav navbar-right navbar-nav">
						<li class=""><a href="/login">Vote</a></li>
						<li class="router-link-exact-active active"><a href="/dashboard">Final Results</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="header">
			<h1>Nominations Results "DEMO COMPANY NAME elections 2022"</h1>
		</div>

		<div class="container">
			<div v-for="position in positions" :key="position.id">
				<div class="section">
					<h2>{{ position.name }}</h2>
					<div class="candidate-list">
						<div class="candidate" v-for="nominee in nominees" v-if="nominee.position_id == position.id"
							:key="nominee.id">
							<img src="https://via.placeholder.com/100" alt="Hamisi Mtengeli">
							<span>{{ nominee['name'] }}</span>
							<span>{{ nominee['total_votes'] }} Vote(s)</span>
						</div>
					</div>
					<div class="chart" v-for="nominee in nominees" v-if="nominee.position_id == position.id"
						:key="nominee.id">
						<span :style="{width:  Math.round((nominee['total_votes'] / getTotalVotePerPosition(nominee.position_id)) * 100) +'%'}">{{ nominee['name'] }} </span>
					</div>
				</div>
			</div>

			<div class="footer">
				&copy; 2024 E-VOTING - All rights reserved.
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data: function () {
		return {
			nominees: [],
			results: [],
			votes: [],
			partylists: [],
			positions: []
		}
	},

	methods: {
		getNominee: function (id) {
			let nominees = this.nominees;
			for (var i in nominees)
				if (id == nominees[i]['id'])
					return nominees[i];
			return {};
		},

		getPartylist: function (id) {
			let partylists = this.partylists;
			for (var i in partylists)
				if (id == partylists[i]['id'])
					return partylists[i]['name'];
			return 'No Partylist';
		},

		getTotalVotePerPosition: function (positionIdToFilter) {
			const votes = this.votes
				.filter(item => item.position_id === positionIdToFilter)  // Filter by position_id
				.map(item => item.votes);  //
			return votes;

		},

	},

	created: function () {
		this.util.notify('Loading please wait...', 'loading');
		var vm = this;
		axios.get(config.API + 'dashboard')
			.then(response => {
				$.notifyClose();
				vm.nominees = response.data.nominee;
				vm.results = response.data.result;
				vm.partylists = response.data.partylist;
				vm.positions = response.data.position;
				vm.votes = response.data.votes;
			})
			.catch(error => {
				$.notifyClose();
				vm.util.showResult(error, 'error');
			})
	},

	computed: {
		election_id: function () {
			return this.$route.params.election_id;
		},

		no_votes: function () {
			let nominees = this.nominees;
			let results = this.results;
			let no_votes = [];
			for (var i in nominees) {
				var hasvote = false;
				for (var y in results) {
					if (results[y]['nominee_id'] == nominees[i]['id'])
						hasvote = true;
				}
				if (!hasvote)
					no_votes.push(nominees[i]);
			}
			return no_votes;
		}

	}
}
</script>