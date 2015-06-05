<?php

use DEA\Transformers\MatchTransformer;

class MatchesController extends ApiController {

	/**
	 * @var DEA\Transformers\MatchTransformer
	 */
	protected $matchTransformer;

	function __construct(MatchTransformer $matchTransformer) {
		$this->matchTransformer = $matchTransformer;

		// $this->beforeFilter('auth.basic', ['on' => 'post']);		This is not working
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param null $id
	 * @return Response
	 */
	public function index($userId = null) {
		$limit = Input::get('limit') ? : 30;
		// TODO: max limit that the client can retrieve
		// TODO:
		// findOrFail gives 'Resource not found' if fails
		// $matches = $userId ? User::findOrFail($userId)->matches : Match::all();	change to limits
		$matches = Match::paginate($limit);

		return $this->respondWithPagination($matches, [
			'data' => $this->matchTransformer->transformCollection($matches->all())
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		// TODO: rules in Match, validation(all fields should be filled in)
		// TODO: figure out security in posting a new match preference

		if (Match::whereUserId(Input::get('user_id')) != null) {
			return 'User already has already created a match';
		}

		// Store in database(for now fields can be nullable)
		$match = new Match;
		if ($userId = Input::get('user_id')) {
			$match->user_id = $userId;
		} 

		if ($maxDistance = Input::get('max_distance')) {
			$match->max_distance = $maxDistance;
		}

		if ($minAge = Input::get('min_age')) {
			$match->min_age = $minAge;
		}

		if ($maxAge = Input::get('max_age')) {
			$match->max_age = $maxAge;
		}

		if ($minPrice = Input::get('min_price')) {
			$match->min_price = $minPrice;
		} 

		if ($minPrice = Input::get('max_price')) {
			$match->min_price = $minPrice;
		} 

		if ($comment = Input::get('comment')) {
			$match->comment = $comment;
		}

		if ($gender = Input::get('gender')) {
			$match->gender = $gender;
		}

		if ($startTime = Input::get('start_time')) {
			$match->start_time = $startTime;
		}

		if ($endTime = Input::get('end_time')) {
			$match->end_time = $endTime;
		}

		$match->save();

		$newMatch = Match::whereUserId($userId)->first();
		return $newMatch;
 	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		return Match::whereId($id)->first();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		//
	}


}
