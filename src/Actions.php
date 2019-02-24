<?php

namespace PagOnline;

/**
 * Class Actions.
 */
final class Actions
{
    // Init
    const IGFS_CG_INIT = Init\IgfsCgInit::class;
    const IGFS_CG_SELECTOR = Init\IgfsCgSelector::class;
    const IGFS_CG_VERIFY = Init\IgfsCgVerify::class;

    // Mpi
    const IGFS_CG_MPI_AUTH = Mpi\IgfsCgMpiAuth::class;
    const IGFS_CG_MPI_ENROLL = Mpi\IgfsCgMpiEnroll::class;

    // PayByMail
    const IGFS_CG_PAY_BY_MAIL_INIT = PayByMail\IgfsCgPayByMailInit::class;
    const IGFS_CG_PAY_BY_MAIL_VERIFY = PayByMail\IgfsCgPayByMailVerify::class;

    // Tokenizer
    const IGFS_CG_TOKENIZER_CHECK = Tokenizer\IgfsCgTokenizerCheck::class;
    const IGFS_CG_TOKENIZER_DELETE = Tokenizer\IgfsCgTokenizerDelete::class;
    const IGFS_CG_TOKENIZER_ENROLL = Tokenizer\IgfsCgTokenizerEnroll::class;

    // Tran
    const IGFS_CG_AUTH = Tran\IgfsCgAuth::class;
    const IGFS_CG_CONFIRM = Tran\IgfsCgConfirm::class;
    const IGFS_CG_CREDIT = Tran\IgfsCgCredit::class;
    const IGFS_CG_VOID_AUTH = Tran\IgfsCgVoidAuth::class;
}
