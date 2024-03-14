
if (document.getElementById("back-to-top")) {
	document.body.classList.add("has-back-to-top-id")
}

if (document.getElementsByClassName("back-to-top")) {
	document.body.classList.add("has-back-to-top-class")
}

document.querySelectorAll(".heygov-venue-availability").forEach(venue => {
	console.log(`HeyGov render venue`, venue.id)

	new Vue({
		el: `#${venue.id}`,
		data() {
			return {
				jurisdiction: venue.dataset.jurisdiction,
				venue: venue.dataset.venue,
				months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
				today: new Date(),
				daySelected: new Date(),
				month: null,
				monthDays: 0,
				monthPrev: null,
				monthNext: null,

				availabilityLoaded: [],
				availability: {},
			}
		},
		created() {
			this.loadAvailability(`${this.today.getFullYear()}-${String(this.today.getMonth() + 1).padStart(2, '0')}`).then(
				() => {
					this.daySelected = this.today
				}
			)
			this.setMonth(new Date(this.today.getFullYear(), this.today.getMonth(), 1))
		},
		methods: {
			setMonth(date) {
				this.month = date
				this.monthDays = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate()
				this.monthPrev = new Date(date.getFullYear(), date.getMonth() - 1, 1)
				this.monthNext = new Date(date.getFullYear(), date.getMonth() + 1, 1)

				// load availability for next month
				this.loadAvailability(
					`${this.monthNext.getFullYear()}-${String(this.monthNext.getMonth() + 1).padStart(2, '0')}`
				)
			},
			dateYMD(date) {
				const month = String(date.getMonth() + 1).padStart(2, '0')
				const day = String(date.getDate()).padStart(2, '0')
				return `${date.getFullYear()}-${month}-${day}`
			},
			getMonthDate(dayNumber) {
				return new Date(this.month.getFullYear(), this.month.getMonth(), dayNumber)
			},
			getMonthYMD(dayNumber) {
				return this.dateYMD(this.getMonthDate(dayNumber))
			},
			slotTime(time) {
				time = time.split(':')
				const datetime = new Date(this.daySelected.getFullYear(), this.daySelected.getMonth(), this.daySelected.getDate(), time[0], time[1], time[2])
				return datetime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
			},
			loadAvailability(forDate) {
				return new Promise((resolve, reject) => {
					if (this.availabilityLoaded.includes(forDate)) {
						resolve(this.availability)
					} else {
						fetch(`https://api.heygov.com/${this.jurisdiction}/venues/${this.venue}/availability/${forDate}`)
							.then(response => response.json())
							.then(data => {
								this.availability = { ...this.availability, ...data.days }
								resolve(this.availability)
							})
							.catch(error => {
								alert(`Couldn't load venue availability for ${forDate} (${error})`)
								reject(error)
							})

						this.availabilityLoaded.push(forDate)
					}
				})
			},
		},
	})
})
