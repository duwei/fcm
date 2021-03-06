<?php
/**
 *   @OA\RequestBody(
 *       request="GetGame",
 *       @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              type="object",
 *              @OA\Property(
 *                  property="uuid",
 *                  description="game uuid",
 *                  type="string"
 *              ),
 *          ),
 *          example={
 *             "uuid": "84371b23-3d29-4f09-b533-6ca9dcc1d079",
 *          }
 *       )
 *   )
 */

/**
 *   @OA\RequestBody(
 *       request="Login",
 *       @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              type="object",
 *              @OA\Property(
 *                  property="account",
 *                  description="user account",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="password",
 *                  description="user password",
 *                  type="string"
 *              ),
 *          ),
 *          example={
 *             "account": "mir4account",
 *             "password": "password",
 *          }
 *       )
 *   )
 */

/**
 *   @OA\RequestBody(
 *       request="GetUser",
 *       @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              type="object",
 *              @OA\Property(
 *                  property="access_token",
 *                  description="user access token",
 *                  type="string"
 *              ),
 *          ),
 *          example={
 *             "access_token": "84371b23-3d29-4f09-b533-6ca9dcc1d079",
 *          }
 *       )
 *   )
 */

/**
 *   @OA\RequestBody(
 *       request="RegisterUser",
 *       @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              type="object",
 *              @OA\Property(
 *                  property="account",
 *                  description="user account",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="password",
 *                  description="user password",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="name",
 *                  description="user name",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="id_card",
 *                  description="user id card",
 *                  type="string"
 *              ),
 *          example={
 *             "account": "mir4account",
 *             "password": "password",
 *             "name": "??????",
 *             "id_card": "330329199001020022",
 *          }
 *       )
 *   )
 * )
 */
