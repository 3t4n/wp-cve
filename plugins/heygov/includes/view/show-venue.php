
<?php if (is_wp_error($venue)) : ?>
	<div class="heygov-alert heygov-alert-danger">
		<?php echo $venue->get_error_message(); ?>
	</div>
<?php else : ?>
	<div id="heygov-venue-<?php echo $venue['slug']; ?>" class="heygov-venue-availability" data-jurisdiction="<?php echo $heygov_id; ?>" data-venue="<?php echo $venue['slug']; ?>">
		<div class="heygov-row heygov-mb-3">
			<div class="heygov-col">
				<span
					class="heygov-cursor-pointer"
					:class="{ 'month-disabled': monthPrev.getMonth() < today.getMonth() }"
					@click="setMonth(new Date(month.getFullYear(), month.getMonth() - 1, 1))"
					>← {{ months[monthPrev.getMonth()].slice(0, 3) }}</span
				>
			</div>
			<div class="heygov-col heygov-text-center">
				<strong
					>{{ months[month.getMonth()] }}
					<span v-if="month.getFullYear() !== today.getFullYear()">{{
						month.getFullYear()
					}}</span></strong
				>
			</div>
			<div class="heygov-col heygov-text-end">
				<span
					class="heygov-cursor-pointer"
					@click="setMonth(new Date(month.getFullYear(), month.getMonth() + 1, 1))"
					>{{ months[monthNext.getMonth()].slice(0, 3) }} →</span
				>
			</div>
		</div>

		<div class="heygov-venue-availability-days">
			<div>Sun</div>
			<div>Mon</div>
			<div>Tue</div>
			<div>Wed</div>
			<div>Thu</div>
			<div>Fri</div>
			<div>Sat</div>
		</div>

		<div class="heygov-venue-availability-dates mb-2">
			<div v-for="i in month.getDay()" :key="`venue-availability-day-empty-${i}`">
				&nbsp;
			</div>

			<div
				v-for="day in monthDays"
				:key="`venue-availability-day-${day}`"
				class="venue-availability-date"
				:class="{
					'venue-availability-date-today':
						getMonthDate(day).toLocaleDateString() === today.toLocaleDateString(),
					'venue-availability-date-available':
						availability[getMonthYMD(day)] && availability[getMonthYMD(day)].slotsAvailable,
					'venue-availability-date-selected':
						getMonthDate(day).toLocaleDateString() === daySelected.toLocaleDateString(),
				}"
				@click="daySelected = getMonthDate(day)"
			>
				{{ day }}
			</div>
		</div>

		<div v-if="availability[dateYMD(daySelected)]">
			<h5 class="heygov-mb-1">Availability for {{ daySelected.toLocaleDateString() }}</h5>

			<a
				v-for="slot in availability[dateYMD(daySelected)].slots"
				:key="`venue-slot-${slot.id}`"
				:href="
					`https://app.heygov.com/${jurisdiction}/venues/${venue}/booking/${dateYMD(daySelected)}/${
						slot.id
					}`
				"
				target="heygov"
				class="heygov-venue-slot"
				:class="slot.available ? 'venue-slot-available' : 'venue-slot-unavailable'"
			>
				<span>{{ slotTime(slot.starts_at) }} → {{ slotTime(slot.ends_at) }}</span>
				<span v-if="slot.available" class="venue-slot-book-btn"
					>Book now
					{{
						Number(slot.price).toLocaleString('en-US', {
							style: 'currency',
							currency: 'usd',
						})
					}}</span
				>
				<small v-else>{{ slot.reason }}</small>
			</a>
		</div>
		<p v-else class="text-muted">There are no available slots for {{ daySelected.toLocaleDateString() }}</p>
	</div>
<?php endif ?>
