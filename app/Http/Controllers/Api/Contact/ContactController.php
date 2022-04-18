<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Services\ContactManager\ContactManagerInterface;
use App\Http\Services\UserManager\UserManagerInterface;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    private ContactManagerInterface $contactManager;

    public final function __construct(ContactManagerInterface $contactManager, UserManagerInterface $userManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/contacts",
     *     summary="Get list of user's contacts",
     *     tags={"Contact"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successfull operation",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Contact")
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     )
     * )
     */
    public final function index(): JsonResponse
    {
        return response()->json($this->contactManager->getContactCollection()->toArray());
    }

    /**
     * @OA\Post(
     *     path="/contacts",
     *     summary="Create new contact",
     *     tags={"Contact"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         request="Contact",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="Contact name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="Contact phone",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create new contact",
     *         @OA\JsonContent(ref="#/components/schemas/Contact"),
     *     )
     * )
     *
     * @param StoreContactRequest $request
     * @return JsonResponse
     */
    public final function store(StoreContactRequest $request): JsonResponse
    {
        if ($this->contactManager->addContact($request->all())) {
            return response()->json(['message' => 'Contact created'], Response::HTTP_CREATED);
        } else {
            return response()->json(['message' => 'Failed to create contact'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Get(
     *     path="contacts/{contact}",
     *     summary="Get contact info by contact_id",
     *     tags={"Contact"},
     *     security={{ "bearerAuth":{} }},
     *     description="Get contact info by contact_id",
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         description="contact identifier",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retrieve specified contact",
     *         @OA\JsonContent(ref="#/components/schemas/Contact"),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Contact is not found",
     *     )
     * )
     *
     * @param Contact $contact
     * @return JsonResponse
     */
    public final function show(Contact $contact): JsonResponse
    {
        if ($contact = $this->contactManager->getContact($contact->id)) {
            return response()->json($contact, Response::HTTP_OK);
        } else {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     *     path="contacts/{contact}",
     *     tags={"Contact"},
     *     security={{ "bearerAuth":{} }},
     *     description="Update contact",
     *     @OA\RequestBody(
     *          request="Contact",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Contact")
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="successful operation",
     *         @OA\JsonContent()
     *     )
     * )
     *
     * @param Contact $contact
     * @param UpdateContactRequest $request
     * @return JsonResponse
     */
    public final function update(Contact $contact, UpdateContactRequest $request): JsonResponse
    {
        if ($this->contactManager->updateContact($contact->id, $request->only(['name', 'phone']))) {
            return response()->json(['message' => 'Contact successfully updated'], Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['message' => 'Failed to update contact'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Delete(
     *     path="contacts/{contact}",
     *     tags={"Contact"},
     *     security={{ "bearerAuth":{} }},
     *     summary="Delete contact",
     *     @OA\Parameter(
     *          name="contact",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *        ),
     *     @OA\Response(
     *          response=204,
     *          description="Delete specified contact",
     *          @OA\JsonContent()
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Cannot delete contact",
     *          @OA\JsonContent()
     *      ),
     * )
     *
     * @param Contact $contact
     * @return JsonResponse
     */
    public final function destroy(Contact $contact): JsonResponse
    {
        if ($this->contactManager->deleteContact($contact->id)) {
            return response()->json([], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['message' => 'Failed to delete contact'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Contact $contact
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="contacts/{contact}/favourite",
     *     tags={"Contact"},
     *     security={{ "bearerAuth":{} }},
     *     description="Mark contact as favourite",
     *     @OA\Response(
     *         response=202,
     *         description="successful operation",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function favourite(Contact $contact): JsonResponse
    {
        if ($this->contactManager->markAsFavourite($contact->id)) {
            return response()->json(['message' => 'Contact successfully added to favourite'], Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['message' => 'Failed to add contact to favourite'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
