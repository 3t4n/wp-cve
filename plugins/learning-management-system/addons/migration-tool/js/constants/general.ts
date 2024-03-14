export const ALL_MIGRATION_STEPS: string[] = ['Courses', 'Orders', 'Reviews'];

export const MIGRATION_STEPS_RELATIVE_TO_COURSE: { [key: string]: string[] } = {
	'sfwd-lms': ['Courses', 'Orders'],
};

export const COMPLETED: string = 'completed';
export const MIGRATING: string = 'migrating';
export const COURSES: string = 'courses';
export const ORDERS: string = 'orders';
export const REVIEWS: string = 'reviews';
